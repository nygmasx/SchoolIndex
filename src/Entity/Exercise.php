<?php

namespace App\Entity;

use App\Enum\DifficultyLevelEnum;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
#[Vich\Uploadable]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thematic $thematic = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chapter = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $keywords = null;

    #[ORM\Column(type: 'string', enumType: DifficultyLevelEnum::class)]
    private ?DifficultyLevelEnum $difficulty = null;

    #[ORM\Column(nullable: true)]
    private ?float $duration = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Origin $origin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $originInformation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposedByType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposedByFirstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposedByLastName = null;

    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $exerciseFile = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $correctionFile = null;

    #[Vich\UploadableField(mapping: 'exercises', fileNameProperty: 'exerciseFile', size: 'fileSize', mimeType: 'fileExtension', originalName: 'originalFileName')]
    private ?File $firstFile = null;
    #[Vich\UploadableField(mapping: 'exercises', fileNameProperty: 'correctionFile', size: 'fileSize', mimeType: 'fileExtension', originalName: 'originalFileName')]
    private ?File $secondFile = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'exercises', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private Collection $skills;

    #[ORM\Column(length: 255)]
    private ?string $originalFileName = null;

    #[ORM\Column(length: 255)]
    private ?string $fileExtension = null;

    #[ORM\Column]
    private ?int $fileSize = null;

    public function __construct()
    {
        $this->createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $this->skills = new ArrayCollection();
        $this->course = new Course();

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

    public function setChapter(?string $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): static
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

    public function setDuration(?float $duration): static
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

    public function setOriginName(?string $originName): static
    {
        $this->originName = $originName;

        return $this;
    }

    public function getOriginInformation(): ?string
    {
        return $this->originInformation;
    }

    public function setOriginInformation(?string $originInformation): static
    {
        $this->originInformation = $originInformation;

        return $this;
    }

    public function getProposedByType(): ?string
    {
        return $this->proposedByType;
    }

    public function setProposedByType(?string $proposedByType): static
    {
        $this->proposedByType = $proposedByType;

        return $this;
    }

    public function getProposedByFirstName(): ?string
    {
        return $this->proposedByFirstName;
    }

    public function setProposedByFirstName(?string $proposedByFirstName): static
    {
        $this->proposedByFirstName = $proposedByFirstName;

        return $this;
    }

    public function getProposedByLastName(): ?string
    {
        return $this->proposedByLastName;
    }

    public function setProposedByLastName(?string $proposedByLastName): static
    {
        $this->proposedByLastName = $proposedByLastName;

        return $this;
    }

    public function getExerciseFile(): ?string
    {
        return $this->exerciseFile;
    }

    public function setExerciseFile(?string $exerciseFile): static
    {
        $this->exerciseFile = $exerciseFile;

        return $this;
    }

    public function getCorrectionFile(): ?string
    {
        return $this->correctionFile;
    }

    public function setCorrectionFile(?string $correctionFile): static
    {
        $this->correctionFile = $correctionFile;

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

    public function getCreatedAt(): \DateTimeImmutable|false|string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function removeFiles(): static
    {
        $this->correctionFile = null;
        $this->exerciseFile = null;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): static
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): static
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getFirstFile(): ?File
    {
        return $this->firstFile;
    }

    public function setFirstFile(?File $firstFile = null): void
    {
        $this->firstFile = $firstFile;
    }

    public function setSecondFile(?File $secondFile = null): void
    {
        $this->secondFile = $secondFile;
    }

    public function getSecondFile(): ?File
    {
        return $this->secondFile;
    }


}
