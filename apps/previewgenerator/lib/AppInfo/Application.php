<?php
/**
 * @copyright Copyright (c) 2016, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\PreviewGenerator\AppInfo;

use OCA\PreviewGenerator\Watcher;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\Files\IRootFolder;
use OCP\Files\Node;

class Application extends App {

	/**
	 * Application constructor.
	 *
	 * @param string $appName
	 * @param array $urlParams
	 */
	public function __construct($appName, array $urlParams = []) {
		parent::__construct($appName, $urlParams);

		$container = $this->getContainer();
		$this->connectWatcher($container);
	}

	/**
	 * @param IAppContainer $container
	 */
	private function connectWatcher(IAppContainer $container) {
		/** @var IRootFolder $root */
		$root = $container->query(IRootFolder::class);
		$root->listen('\OC\Files', 'postWrite', function (Node $node) use ($container) {
			/** @var Watcher $watcher */
			$watcher = $container->query(Watcher::class);
			$watcher->postWrite($node);
		});
	}
}
