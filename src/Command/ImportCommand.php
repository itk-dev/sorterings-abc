<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018–2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Command;

use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\ItemDescription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /** @var \Symfony\Component\Console\Output\Output */
    private $output;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('app:import')
            ->addArgument('entityType', InputArgument::REQUIRED, 'Entity type to import: '.implode(', ', [Item::class, ItemCategory::class, ItemDescription::class]))
            ->addArgument('filePath', InputArgument::REQUIRED, 'Path to csv file with data to import');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $entityType = $input->getArgument('entityType');
        $filePath = $input->getArgument('filePath');

        if (!file_exists($filePath)) {
            throw new RuntimeException('File "'.$filePath.'" does not exist.');
        }

        $handle = fopen($filePath, 'r');
        $header = null;
        $data = [];
        while ($row = fgetcsv($handle, 0, ';')) {
            if (null === $header) {
                foreach ($row as &$value) {
                    // Strip BOM!
                    $value = str_replace("\u{feff}", '', $value);
                }
                $header = $row;
            } else {
                $data[] = array_combine($header, array_map('trim', $row));
            }
        }

        switch ($entityType) {
            case Item::class:
                $this->importItem($data);

                break;
            case ItemCategory::class:
                $this->importItemCategory($data);

                break;
            case ItemDescription::class:
                $this->importItemDescription($data);

                break;
            default:
                throw new RuntimeException('Invalid entityType: '.$entityType);
        }
    }

    private function importItem(array $data)
    {
        $repository = $this->entityManager->getRepository(Item::class);
        foreach ($data as $item) {
            $name = $item['Searchword'] ?? null;
            $descriptionName = $item['Description_name1'];

            if (empty($name)) {
                continue;
            }

            $itemDescription = $this->getItemDescription($descriptionName, true);
            if (null === $itemDescription) {
                $this->output->writeln('Missing or invalid description: '.$itemDescription);

                continue;
            }

            $itemItem = $repository->findOneBy(['name' => $name]);
            if (null === $itemItem) {
                $itemItem = new Item();
            }
            $itemItem
                ->setName($name)
                ->setDescription($itemDescription);

            for ($i = 1; $i <= 3; ++$i) {
                $key = 'Category_name'.$i;
                $categoryName = $item[$key] ?? null;
                if (!empty($categoryName)) {
                    $itemCategory = $this->getItemCategory($categoryName, true);
                    $itemItem->addCategory($itemCategory);
                }
            }

            $this->entityManager->persist($itemItem);
            $this->entityManager->flush();

            $this->output->writeln($itemItem);
        }
    }

    private function importItemCategory(array $data)
    {
        $repository = $this->entityManager->getRepository(ItemCategory::class);
        foreach ($data as $item) {
            $name = $item['Category_Name'] ?? null;
            $description = $item['Category_description'] ?? null;
            $icon = $item['Category_Icon'] ?? null;

            if (empty($name)) {
                continue;
            }

            $itemCategory = $repository->findOneBy(['name' => $name]);
            if (null === $itemCategory) {
                $itemCategory = new ItemCategory();
            }
            $itemCategory
                ->setName($name)
                ->setDescription($description)
                ->setIcon($icon);
            $this->entityManager->persist($itemCategory);
            $this->entityManager->flush();

            $this->output->writeln($itemCategory);
        }
    }

    private function importItemDescription(array $data)
    {
        $repository = $this->entityManager->getRepository(ItemDescription::class);
        foreach ($data as $item) {
            $name = $item['Description_Name'] ?? null;
            $description = $item['Description_Text'] ?? null;

            if (empty($name)) {
                continue;
            }

            $itemDescription = $repository->findOneBy(['name' => $name]);
            if (null === $itemDescription) {
                $itemDescription = new ItemDescription();
            }
            $itemDescription
                ->setName($name)
                ->setDescription($description);
            $this->entityManager->persist($itemDescription);
            $this->entityManager->flush();

            $this->output->writeln($itemDescription);
        }
    }

    private function getItemCategory($name, $createIfMissing = false)
    {
        $itemCategory = $this->entityManager
            ->getRepository(ItemCategory::class)
            ->findOneBy(['name' => $name]);

        if (null === $itemCategory && $createIfMissing) {
            $itemCategory = new ItemCategory();
            $itemCategory->setName($name)->setDescription('');
            $this->entityManager->persist($itemCategory);
            $this->entityManager->flush();
        }

        return $itemCategory;
    }

    private function getItemDescription($name, $createIfMissing = false)
    {
        $itemDescription = $this->entityManager
            ->getRepository(ItemDescription::class)
            ->findOneBy(['name' => $name]);

        if (null === $itemDescription && $createIfMissing) {
            $itemDescription = new ItemDescription();
            $itemDescription->setName($name);
            $this->entityManager->persist($itemDescription);
            $this->entityManager->flush();
        }

        return $itemDescription;
    }
}
