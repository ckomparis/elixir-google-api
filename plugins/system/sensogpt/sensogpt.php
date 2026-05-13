<?php

declared('JPATH_BASE') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\DispatcherInterface;
use SensoGPT\Plugin\System\SensoGPT\Extension\SensoGPT;

return new SensoGPT($this->dispatcher, (array) $this);
