<?php
namespace Bountin\Backup;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Application
	extends BaseApplication
{

	public function run(InputInterface $input = null, OutputInterface $output = null)
	{
		if (null === $output) {
			$styles['highlight'] = new OutputFormatterStyle('red');
			$styles['warning'] = new OutputFormatterStyle('black', 'yellow');
			$formatter = new OutputFormatter(null, $styles);
			$output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $formatter);
		}

		return parent::run($input, $output);
	}

	public function doRun()
	{
		$home_path = getenv('HOME');
		$configuration_path = $home_path.'/.bountin-backup';


		if (!file_exists($configuration_path)) {
			throw new \RuntimeException('Configuration file not found!'.PHP_EOL.'Looking at '.$configuration_path);
		}

		$configuration = json_decode(file_get_contents($configuration_path), true);
		if (empty($configuration)) {
			throw new \RuntimeException('Configuration is empty');
		}

		$dropbox_path = $configuration['dropbox'];
		$dropbox_path = str_replace('~', $home_path, $dropbox_path);
		$dropbox_path .= '/bountin-backup/';

		$pocket_url = sprintf(
			'https://readitlaterlist.com/v2/get?apikey=%s&username=%s&password=%s',
			$configuration['pocket_api_key'],
			$configuration['pocket_user'],
			$configuration['pocket_password']
		);
		$pocket = file_get_contents($pocket_url);

		if (!file_exists($dropbox_path)) {
			mkdir($dropbox_path, 0600);
		}

		file_put_contents($dropbox_path.'pocket.json', $pocket);
	}
}