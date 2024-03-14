function prefersReducedMotion () {
  if (typeof window !== 'undefined') {
    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    return !mediaQuery || mediaQuery.matches
  } else {
    return true
  }
}

function motionSafe () {
  return !prefersReducedMotion()
}

function animationsEnd (selector) {
  const elements = [...document.querySelectorAll(selector)];

  return Promise.all(elements.map((element) => {
    return new Promise((resolve) => {
      function listener () {
        element.removeEventListener('animationend', listener);
        resolve();
      }
      element.addEventListener('animationend', listener);
    })
  }))
}

function pascalCase (string) {
  return string.split(/[^\w]/).map(capitalize).join('')
}

function camelCase (string) {
  return string.split(/[^\w]/).map(
    (w, i) => i === 0 ? w.toLowerCase() : capitalize(w)
  ).join('')
}

function capitalize (string) {
  return string.replace(/^\w/, (c) => c.toUpperCase())
}

class NullTurn {
  static supported = true
  direction = 'none'
  exit () {}
  async beforeEnter () {}
  async enter () {}
  complete () {}
  abort () {}
  finished = Promise.resolve()
}

class Animations {
  constructor (selector) {
    this.selector = selector;
    this.ended = new Promise((resolve) => { this.resolve = resolve; });
  }

  async start (applyAnimations) {
    applyAnimations();
    const elements = [...document.querySelectorAll(this.selector)];

    if (elements.every((e) => e.getAnimations().length)) {
      await animationsEnd(this.selector);
      this.resolve();
    } else {
      this.resolve();
    }
  }
}

const DEFAULT_OPTIONS = {
  animateRestore: false
};

class BaseTurn {
  constructor (action, direction = 'none', options = {}) {
    this.action = action;
    this.direction = direction;
    this.options = { ...DEFAULT_OPTIONS, ...options };
    this.beforeExitClasses = new Set();
    this.exitClasses = new Set();
    this.enterClasses = new Set();
    this.beforeTransitionClasses = new Set();
    this.transitionClasses = new Set();
    this.forwardClasses = new Set();
    this.backClasses = new Set();
    this.noneClasses = new Set();
  }

  addClasses (type) {
    document.documentElement.classList.add(`turn-${type}`);

    Array.from(document.querySelectorAll(`[data-turn-${type}]`)).forEach((element) => {
      element.dataset[`turn${pascalCase(type)}`].split(/\s+/).forEach((klass) => {
        if (klass) {
          element.classList.add(klass);
          this[`${camelCase(type)}Classes`].add(klass);
        }
      });
    });
  }

  removeClasses (type) {
    document.documentElement.classList.remove(`turn-${type}`);

    Array.from(document.querySelectorAll(`[data-turn-${type}]`)).forEach((element) => {
      this[`${camelCase(type)}Classes`].forEach((klass) => element.classList.remove(klass));
    });
  }

  dispatch (eventName, { target = document, detail = {}, bubbles = true, cancelable = true } = {}) {
    const type = `turn:${eventName}`;
    const event = new window.CustomEvent(type, { detail, bubbles, cancelable });
    target.dispatchEvent(event);
    return event
  }
}

class AnimationTurn extends BaseTurn {
  static supported = true

  exit (detail) {
    const exitAnimations = new Animations('[data-turn-exit]');

    let resolveExit;
    this.animateOut = Promise.all([
      exitAnimations.ended,
      new Promise((resolve) => { resolveExit = resolve; })
    ]);

    this.addClasses('before-exit');
    this.dispatch('before-exit', { detail });

    window.requestAnimationFrame(() => {
      exitAnimations.start(() => {
        this.addClasses(this.direction);
        this.addClasses('exit');
      });
      this.removeClasses('before-exit');
      resolveExit();
    });
  }

  async beforeEnter (detail) {
    await this.animateOut;
    this.removeClasses('exit');
    if (this.animateIn) await this.animateIn; // only present on post-preview enters
    else {
      this.dispatch('before-enter', {
        detail: { ...detail, action: this.action }
      });
    }
  }

  enter () {
    const enterAnimations = new Animations('[data-turn-enter]');
    this.animateIn = enterAnimations.ended;
    enterAnimations.start(() => this.addClasses('enter'));
    return this.animateIn
  }

  async complete (detail) {
    this.removeClasses('enter');
    this.removeClasses(this.direction);
    this.dispatch('enter', { detail: { ...detail, action: this.action } });
  }

  abort () {
    this.removeClasses('before-exit');
    this.removeClasses('exit');
    this.removeClasses('enter');
    this.removeClasses(this.direction);
  }

  get finished () {
    return this.animateIn
  }
}

class ViewTransitionTurn extends BaseTurn {
  static supported = !!document.startViewTransition

  prepare () {
    this.snapshot = new Promise(resolve => { this.snapshat = resolve; });
    this.transition = document.startViewTransition(_ => this.render());
    return this.snapshot
  }

  exit () {}

  async beforeEnter (detail) {
    this.addClasses('before-transition');
    this.dispatch('before-transition', {
      detail: { ...detail, action: this.action }
    });
    await this.prepare();
  }

  render () {
    this.snapshat();
    return new Promise(resolve => { this.rendered = resolve; })
  }

  async enter () {
    this.rendered();
    this.removeClasses('before-transition');
    this.addClasses('transition');
    await this.finished;
    await Promise.resolve(); // next tick
    this.removeClasses('transition');
  }

  complete () {}

  abort () {
    this.removeClasses('transition');
  }

  rendered () {}

  get finished () {
    return this.transition?.finished
  }
}

const VIEW_TRANSITIONS = 'turn-view-transitions';
const NO_VIEW_TRANSITIONS = 'turn-no-view-transitions';
const ACTIONS = ['advance', 'restore', 'replace'];

class Controller {
  constructor (config) {
    this.config = config;
  }

  start () {
    this.animationTurn = new NullTurn();
    this.viewTransitionTurn = new NullTurn();
    addSupportClass(this.config);
    this.currentUrl = window.location.toString();
  }

  stop () {
    this.animationTurn.abort();
    this.viewTransitionTurn.abort();
    this.animationTurn = new NullTurn();
    this.viewTransitionTurn = new NullTurn();
    removeSupportClasses();
    delete this.initiator;
    delete this.currentUrl;
  }

  click (event) {
    this.initiator = event.target;
  }

  submitStart (event) {
    this.initiator = event.target;
  }

  visit (event) {
    this.reset(event);

    this.animationTurn = create(AnimationTurn, event.detail.action, event.detail.direction);
    this.viewTransitionTurn = create(ViewTransitionTurn, event.detail.action, event.detail.direction);

    this.animationTurn.exit({
      ...event.detail,
      referrer: this.currentUrl,
      initiator: this.initiator
    });
  }

  async beforeRender (event) {
    event.preventDefault();

    const detail = {
      newBody: event.detail.newBody,
      referrer: this.currentUrl,
      initiator: this.initiator
    };
    await this.animationTurn.beforeEnter(detail);

    this.hasPreview
      ? await this.viewTransitionTurn.finished
      : await this.viewTransitionTurn.beforeEnter(detail);

    if (this.isPreview) this.hasPreview = true;
    event.detail.resume();
  }

  render () {
    this.currentUrl = window.location.toString();
    delete this.initiator;

    const isInitialRender = this.isPreview || !this.hasPreview;
    if (isInitialRender) {
      this._render = this.viewTransitionTurn.enter()
        .then(() => this.animationTurn.enter());
    }
  }

  async load (event) {
    await this._render;
    removeActionClasses();
    this.animationTurn.complete({
      ...event.detail,
      referrer: this.currentUrl
    });
  }

  popstate (event) {
    const fixNonRestoreBack = this.animationTurn.action !== 'restore';
    fixNonRestoreBack && this.animationTurn.abort();
  }

  get isPreview () {
    return document.documentElement.hasAttribute('data-turbo-preview')
  }

  reset (event) {
    removeActionClasses();
    addActionClass(event.detail.action);
    this.hasPreview = undefined;
    this._render = undefined;
    this.animationTurn.abort();
    this.viewTransitionTurn.abort();
    if (event.detail.action === 'restore' || !this.initiator) {
      this.initiator = document.documentElement;
    }
  }
}

function addSupportClass (config) {
  document.documentElement.classList.add(
    ViewTransitionTurn.supported ? VIEW_TRANSITIONS : NO_VIEW_TRANSITIONS
  );
}

function removeSupportClasses () {
  document.documentElement.classList.remove(
    ViewTransitionTurn.supported
      ? VIEW_TRANSITIONS
      : NO_VIEW_TRANSITIONS
  );
}

function addActionClass (action) {
  document.documentElement.classList.add(`turn-${action}`);
}

function removeActionClasses () {
  const classList = document.documentElement.classList;
  classList.remove.apply(classList, ACTIONS.map(a => `turn-${a}`));
}

function create (Klass, action, direction) {
  if (!Klass.supported || document.body.dataset.turn === 'false') {
    Klass = NullTurn;
  }
  const options = JSON.parse(document.body.dataset.turnOptions || '{}');
  return new Klass(action, direction, options)
}

const Turn = {
  start () {
    if (!this.started && motionSafe()) {
      for (const event in eventListeners) {
        window.addEventListener(event, eventListeners[event]);
      }
      this.controller = new Controller(Turn.config);
      this.controller.start();
      this.started = true;
    }
  },

  stop () {
    if (this.started) {
      for (const event in eventListeners) {
        window.removeEventListener(event, eventListeners[event]);
      }
      this.controller.stop();
      this.started = false;
    }
  },

  config: {
    experimental: {
      viewTransitions: true
    }
  }
};

const eventListeners = {
  'turbo:click': function (event) {
    this.controller.click(event);
  }.bind(Turn),
  'turbo:visit': function (event) {
    this.controller.visit(event);
  }.bind(Turn),
  'turbo:submit-start': function (event) {
    this.controller.submitStart(event);
  }.bind(Turn),
  'turbo:before-render': async function (event) {
    this.controller.beforeRender(event);
  }.bind(Turn),
  'turbo:render': async function () {
    this.controller.render();
  }.bind(Turn),
  'turbo:load': async function (event) {
    this.controller.load(event);
  }.bind(Turn),
  popstate: function () {
    this.controller.popstate();
  }.bind(Turn)
};

export { Turn as default };
