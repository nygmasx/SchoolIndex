<?php

namespace App\Entity;

use App\Enum\DifficultyLevelEnum;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\File;

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

    #[ORM\Column(type: 'string', enumType: DifficultyLevelEnum::class)]
    private ?DifficultyLevelEnum $difficulty = null;

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

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $exerciseFile = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $correctionFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct(
        ?string              $name = null,
        ?Course              $course = null,
        ?Classroom           $classroom = null,
        ?Thematic            $thematic = null,
        ?string              $chapter = null,
        ?string              $keywords = null,
        ?DifficultyLevelEnum $difficulty = null,
        ?float               $duration = null,
        ?Origin              $origin = null,
        ?string              $originName = null,
        ?string              $originInformation = null,
        ?string              $proposedbyType = null,
        ?string              $proposedByFirstName = null,
        ?string              $proposedByLastName = null,
        ?User                $createdBy = null,
        ?File                $exerciseFile = null,
        ?File                $correctionFile = null,
    )
    {
        $this->skill = new ArrayCollection();
        $this->name = $name;
        $this->course = $course;
        $this->classroom = $classroom;
        $this->thematic = $thematic;
        $this->chapter = $chapter;
        $this->keywords = $keywords;
        $this->difficulty = $difficulty;
        $this->duration = $duration;
        $this->origin = $origin;
        $this->originName = $originName;
        $this->originInformation = $originInformation;
        $this->proposedbyType = $proposedbyType;
        $this->proposedByFirstName = $proposedByFirstName;
        $this->proposedByLastName = $proposedByLastName;
        $this->createdBy = $createdBy;
        $this->exerciseFile = $exerciseFile;
        $this->correctionFile = $correctionFile;
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

    public function getDifficulty(): ?DifficultyLevelEnum
    {
        return $this->difficulty;
    }

    public function setDifficulty(DifficultyLevelEnum $difficulty): static
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getExerciseFile(): ?File
    {
        return $this->exerciseFile;
    }

    public function setExerciseFile(?File $exerciseFile): static
    {
        $this->exerciseFile = $exerciseFile;

        if ($exerciseFile !== null && $exerciseFile->getExercise() !== $this) {
            $exerciseFile->setExercise($this);
        }

        return $this;
    }

    public function getCorrectionFile(): ?File
    {
        return $this->correctionFile;
    }

    public function setCorrectionFile(File $correctionFile): static
    {
        $this->correctionFile = $correctionFile;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
