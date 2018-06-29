<?php

namespace AppBundle\Command;

use AppBundle\Services\UserServiceInterface;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDateCommand extends ContainerAwareCommand {

	/**
	 * @var UserServiceInterface
	 */
	private $userService;

	public function __construct( UserServiceInterface $userService ) {
		parent::__construct();
		$this->userService = $userService;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this
			->setName( 'app:user_date_command' )
			->setDescription( 'Hello PhpStorm' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$message = $this->userService->checkRegisteredUserDate();
		$output->write($message);
	}
}
