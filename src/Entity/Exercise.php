<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thematic $thematic = null;

    #[ORM\Column(length: 255)]
    private ?string $chapter = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $keywords = null;

    #[ORM\Column]
    private ?int $difficulty = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Origin $origin = null;

    #[ORM\Column(length: 255)]
    private ?string $originName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $originInformation = null;

    #[ORM\Column(length: 255)]
    private ?string $proposedbyType = null;

    #[ORM\Column(length: 255)]
    private ?string $proposedByFirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $proposedByLastName = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'exercises')]
    private Collection $skill;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: File::class)]
    private Collection $exerciseFile;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: File::class)]
    private Collection $correctionFile;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->skill = new ArrayCollection();
        $this->exerciseFile = new ArrayCollection();
        $this->correctionFile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getThematic(): ?Thematic
    {
        return $this->thematic;
    }

    public function setThematic(?Thematic $thematic): static
    {
        $this->thematic = $thematic;

        return $this;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(string $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getOrigin(): ?Origin
    {
        return $this->origin;
    }

    public function setOrigin(?Origin $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getOriginName(): ?string
    {
        return $this->originName;
    }

    public function setOriginName(string $originName): static
    {
        $this->originName = $originName;

        return $this;
    }

    public function getOriginInformation(): ?string
    {
        return $this->originInformation;
    }

    public function setOriginInformation(string $originInformation): static
    {
        $this->originInformation = $originInformation;

        return $this;
    }

    public function getProposedbyType(): ?string
    {
        return $this->proposedbyType;
    }

    public function setProposedbyType(string $proposedbyType): static
    {
        $this->proposedbyType = $proposedbyType;

        return $this;
    }

    public function getProposedByFirstName(): ?string
    {
        return $this->proposedByFirstName;
    }

    public function setProposedByFirstName(string $proposedByFirstName): static
    {
        $this->proposedByFirstName = $proposedByFirstName;

        return $this;
    }

    public function getProposedByLastName(): ?string
    {
        return $this->proposedByLastName;
    }

    public function setProposedByLastName(string $proposedByLastName): static
    {
        $this->proposedByLastName = $proposedByLastName;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skill->contains($skill)) {
            $this->skill->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skill->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getExerciseFile(): Collection
    {
        return $this->exerciseFile;
    }

    public function addExerciseFile(File $exerciseFile): static
    {
        if (!$this->exerciseFile->contains($exerciseFile)) {
            $this->exerciseFile->add($exerciseFile);
            $exerciseFile->setExercise($this);
        }

        return $this;
    }

    public function removeExerciseFile(File $exerciseFile): static
    {
        if ($this->exerciseFile->removeElement($exerciseFile)) {
            // set the owning side to null (unless already changed)
            if ($exerciseFile->getExercise() === $this) {
                $exerciseFile->setExercise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getCorrectionFile(): Collection
    {
        return $this->correctionFile;
    }

    public function addCorrectionFile(File $correctionFile): static
    {
        if (!$this->correctionFile->contains($correctionFile)) {
            $this->correctionFile->add($correctionFile);
            $correctionFile->setExercise($this);
        }

        return $this;
    }

    public function removeCorrectionFile(File $correctionFile): static
    {
        if ($this->correctionFile->removeElement($correctionFile)) {
            // set the owning side to null (unless already changed)
            if ($correctionFile->getExercise() === $this) {
                $correctionFile->setExercise(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
