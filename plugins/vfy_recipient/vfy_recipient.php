<?php

/**
 *  Secure Email Portal
 *  vfy_recipient - Check if user allowed to send message to this recipient
 *
 *  Questions to Max Kostikov - E: max@kostikov.co
 */

class vfy_recipient extends rcube_plugin {

	public $task = 'mail';
	public $rc;

	function init() {

		$this->rc = rcube::get_instance();

		if ($this->rc->task == 'mail') {

        		$this->add_hook('message_before_send', [ $this, 'vfy_recipient_main' ]);
		}
	}


	function vfy_recipient_main($args) {

		$db	= $this->rc->get_dbh();
		$table	= $db->table_name('seen_domains', true);

		// Get allowed domains list
		$domains = [];
		$autocreated = false;
		$r = $db->query("SELECT `seen_domain`, `autocreated` FROM $table WHERE `mailbox` ='" . $args['from'] . "'");
		while($record = $db->fetch_assoc($r)) {
			$domains[] = $record['seen_domain'];
			if( $record['autocreated'])
				$autocreated = true;
		}

		if($autocreated) {

			if(strpos($args['mailto'], ","))
				$rcpts =  explode(", ", $args['mailto']);
			else
				$rcpts[] = $args['mailto'];

			foreach($rcpts as $mailto) {

				preg_match('/<(.*?)>/', $mailto, $match);
				$rcpdom = explode('@', ($match[1] ? $match[1] : $mailto))[1];
				if(! in_array($rcpdom, $domains)) {
					$args['error'] = "You are not allowed to send e-mail to " . $rcpdom . " domain";
					$args['abort'] = true;
					break;
				}
			}

 			$this->load_config();
			$newfrom = $this->rc->config->get('vfy_new_sender', false);

			if($newfrom) {
				$args['message']->headers([ 'From' => '"On behalf of ' . $args['from'] . '" <' . $newfrom . '>'], true);
				$args['from'] = $newfrom;
			}
		}

		//rcube::write_log('debug', __FUNCTION__ . ': args: ' . print_r($args,true));
		return $args;
	}
}
