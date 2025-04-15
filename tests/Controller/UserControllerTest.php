<?php
namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testClientCannotAccessAdminPage(): void
    {
        $user = new User();
        $user->setEmail('nass@ouachen.com');
        $user->setFirstname('Nassim');
        $user->setLastname('Ouachen');
        $user->setSlug('nass-ouachen');
        $user->setPassword(
            static::getContainer()->get(UserPasswordHasherInterface::class)
                ->hashPassword($user, 'Pa$$w0rd!')
        );
        $user->setRole('CLIENT');

        $em = static::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/nouvel-utilisateur');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testComptableCanAccessContrats(): void
    {
        $user = new User();
        $user->setEmail('moi@moi.com');
        $user->setFirstname('moi');
        $user->setLastname('moi');
        $user->setSlug('moi-moi');
        $user->setPassword(
            static::getContainer()->get(UserPasswordHasherInterface::class)
                ->hashPassword($user, 'Pa$$w0rd!')
        );
        $user->setRole('COMPTA');

        $em = static::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/contrat');
        $this->assertResponseStatusCodeSame(200);

    }

    public function testAdminCanCreateUser(): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstname('admin');
        $admin->setLastname('admin');
        $admin->setSlug('tiph-admin');
        $admin->setPassword(
            static::getContainer()->get(UserPasswordHasherInterface::class)
                ->hashPassword($admin, 'Pa$$w0rd!')
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $em = static::getContainer()->get('doctrine')->getManager();
        $em->persist($admin);
        $em->flush();
        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/nouvel-utilisateur');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form([
            'user[firstname]' => 'toi',
            'user[lastname]' => 'toi',
            'user[email]' => 'toi@toi.com',
            'user[password]' => 'Pa$$w0rd!',
            'user[role]' => ['COLLAB'],
            'user[slug]' => 'toi-toi', 
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/utilisateur');

        $user = $this->getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneByEmail('toi@toi.com');
        $this->assertNotNull($user);
        $this->assertContains('COLLAB', $user->getRole());
    }

}
