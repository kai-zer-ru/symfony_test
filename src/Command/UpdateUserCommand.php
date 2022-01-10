<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\UserLog;
use App\Repository\UserLogRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-user',
    description: 'Add a short description for your command',
)]
class UpdateUserCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = 1000;
        $offset = 0;
        while (true) {
            $users = $this->userRepository->findBy(['log' => true], null, $limit, $offset);
            if (count($users) == 0) {
                break;
            }
            $offset += $limit;
            foreach ($users as $user) {
                if ($user->getScore() < 100 || $user->getScore() > 900) {
                    $log = new UserLog();
                    $log->setUserId($user->getId());
                    $log->setScore($user->getScore());
                    $log->setCreatedAt(new \DateTimeImmutable());
                    $this->entityManager->persist($log);
                } else {
                    $user->setLog(false);
                    $this->entityManager->persist($user);
                }
            }
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
