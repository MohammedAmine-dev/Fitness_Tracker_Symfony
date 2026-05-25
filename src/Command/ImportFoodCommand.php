<?php

namespace App\Command;

use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:import-food',
    description: 'Imports food data from OpenFoodFacts based on project implementation plan.',
)]
class ImportFoodCommand extends Command
{
    private const CATEGORY_MAP = [
        'apple' => 'Fruits',
        'banana' => 'Fruits',
        'carrot' => 'Vegetables',
        'chicken' => 'Protein',
        'beef' => 'Protein',
        'rice' => 'Grains',
        'milk' => 'Dairy',
        'chips' => 'Snacks',
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit per term', 50);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = (int) $input->getOption('limit');

        $io->title('Starting Food Import from OpenFoodFacts');

        $endpoint = $_ENV['OPENFOODFACTS_ENDPOINT'] ?? 'https://world.openfoodfacts.org/cgi/search.pl';
        $userAgent = $_ENV['OPENFOODFACTS_USER_AGENT'] ?? 'FitnessTrackerDev/1.0';

        foreach (self::CATEGORY_MAP as $term => $category) {
            $io->text("Fetching $term for category: $category...");

            $response = $this->httpClient->request('GET', $endpoint, [
                'headers' => [
                    'User-Agent' => $userAgent,
                ],
                'query' => [
                    'search_terms' => $term,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page_size' => $limit,
                ]
            ]);

            $data = $response->toArray();

            $imported = 0;
            foreach ($data['products'] ?? [] as $product) {
                if (empty($product['product_name'])) {
                    continue;
                }

                $nutriments = $product['nutriments'] ?? [];
                
                // Skip if no energy data
                if (!isset($nutriments['energy-kcal_100g'])) {
                    continue;
                }

                $food = $this->entityManager->getRepository(Food::class)->findOneBy(['sourceProductId' => $product['id'] ?? '']);
                if (!$food) {
                    $food = new Food();
                    $food->setSource('openfoodfacts');
                    $food->setSourceProductId($product['id'] ?? uniqid());
                }

                $food->setName($product['product_name']);
                $food->setCategory($category);
                $food->setCalories((string) ($nutriments['energy-kcal_100g'] ?? 0));
                $food->setProtein((string) ($nutriments['proteins_100g'] ?? 0));
                $food->setCarbs((string) ($nutriments['carbohydrates_100g'] ?? 0));
                $food->setFat((string) ($nutriments['fat_100g'] ?? 0));
                $food->setServing('100g');
                $food->setImageUrl($product['image_front_url'] ?? null);

                $this->entityManager->persist($food);
                $imported++;
            }

            $this->entityManager->flush();
            $io->success("Imported $imported items for $term");
        }

        $io->success('Food data import complete!');

        return Command::SUCCESS;
    }
}

