<?php

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\TestCase;

class CreateAdvertisementTest extends KernelTestCase
{
    private $advertisementRepository;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $container = self::$kernel->getContainer();
        $this->advertisementRepository = $container->get(AdvertisementRepository::class);
    }

    public function testCreateAdvertisement()
    {
        $advertisement = new Advertisement();
        $advertisement->setName('My Test Ad');
        $advertisement->setType('Apartment');
        $advertisement->setDescription('This is a test ad description');
        $advertisement->setCity('Paris');
        $advertisement->setAddress('10 rue de la paix');
        $advertisement->setZipcode('75000');
        $advertisement->setDateStart(new \DateTime());
        $advertisement->setDateEnd(new \DateTime());

        $this->advertisementRepository->save($advertisement);

        $savedAdvertisement = $this->advertisementRepository->findOneBy([
            'name' => 'My Test Ad',
        ]);

        $this->assertEquals('My Test Ad', $savedAdvertisement->getName());
        $this->assertEquals('Apartment', $savedAdvertisement->getType());
        $this->assertEquals('This is a test ad description', $savedAdvertisement->getDescription());
        $this->assertEquals('Paris', $savedAdvertisement->getCity());
        $this->assertEquals('10 rue de la paix', $savedAdvertisement->getAddress());
        $this->assertEquals('75000', $savedAdvertisement->getZipcode());
    }

    public function testGetAdvertisements()
    {
        $advertisements = $this->advertisementRepository->findAll();

        $this->assertGreaterThan(0, count($advertisements));
    }

    public function testPutAdvertisement()
    {
        $advertisement = $this->advertisementRepository->findOneBy([
            'name' => 'My Test Ad',
        ]);

        $advertisement->setName('My Test Ad 2');

        $this->advertisementRepository->save($advertisement);

        $savedAdvertisement = $this->advertisementRepository->findOneBy([
            'name' => 'My Test Ad 2',
        ]);

        $this->assertEquals('My Test Ad 2', $savedAdvertisement->getName());
    }
}
