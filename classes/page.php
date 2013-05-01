<?

namespace Eternity\Wiki;

class page {
	private $assets;
	private $metadata;
	private $title;
	private $tpl;
	private $content;
	private $ete_header;
	private $ete_notifications;
	private $ete_sidebar;
	private $ete_footer;
	private $wiki_header;
	private $wiki_footer;
	public $generated = false;
	
	function __construct($assets = "", $metadata = "", $content = "", $title = "", $customfooter = true) {
		global $db;
		
		$this->assets = $assets;
		$this->metadata = $metadata;
		$this->content = $content;
		$this->title = $title;
		$this->tpl = file_get_contents(WIKI_ROOT . '/includes/templates/main.tpl');
		$this->generate($customfooter);
	}
	
	function metadata() {
		global $db;
		
		ob_start();
		?><?
		include SRV_ROOT . '/includes/eternityx/meta.php';
		echo $this->metadata;
		$this->metadata = trim(ob_get_contents());
		$this->tpl = str_replace('<metadata>', $this->metadata, $this->tpl);
		ob_end_clean();
	}
	
	function assets() {
		global $db;
		
		ob_start();
		$defaulticon = false;
		include SRV_ROOT . "/includes/eternityx/assets.php";
		?>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
			<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
			
			<link rel="stylesheet" href="/style/style.css" />
			<script src="/js/behavior.js"></script>
<script src="/js/Menu.js"></script>
		<?
		echo $this->assets;
		$this->assets = trim(ob_get_contents());
		$this->tpl = str_replace('<assets>', $this->assets, $this->tpl);
		ob_end_clean();
	}
	
	function title() {
		global $db;
		
		ob_start();
		echo '<title>' . $this->title . '</title>';
		$this->title = trim(ob_get_contents());
		$this->tpl = str_replace('<title>', $this->title, $this->tpl);
		ob_end_clean();
	}
	
	function ete_header() {
		global $db;
		
		ob_start();
		include SRV_ROOT . "/includes/interface/header.php";
		$this->ete_header = trim(ob_get_contents());
		$this->tpl = str_replace('<ete_header>', $this->ete_header, $this->tpl);
		ob_end_clean();
	}
	
	function ete_notifications() {
		global $db, $config, $ccollab_config, $projects_config;
		
		ob_start();
		include SRV_ROOT . "/includes/interface/notifications.php";
		$this->ete_notifications = trim(ob_get_contents());
		$this->tpl = str_replace('<ete_notifications>', $this->ete_notifications, $this->tpl);
		ob_end_clean();
	}
	
	function ete_sidebar() {
		global $db;
		
		ob_start();
		include SRV_ROOT . "/includes/interface/sidebar.php"; 
		$this->ete_sidebar = trim(ob_get_contents());
		$this->tpl = str_replace('<ete_sidebar>', $this->ete_sidebar, $this->tpl);
		ob_end_clean();
	}
	
	function ete_footer() {
		global $db;
		
		ob_start();
		include SRV_ROOT . "/includes/interface/footer.php"; 
		$this->ete_footer = trim(ob_get_contents());
		$this->tpl = str_replace('<ete_footer>', $this->ete_footer, $this->tpl);
		ob_end_clean();
	}
	
	function wiki_header() {
		global $db;
		
		ob_end_clean();
		ob_start();
		include WIKI_ROOT . "/includes/interface/header.php";
		$this->wiki_header = trim(ob_get_contents());
		$this->tpl = str_replace('<wiki_header>', $this->wiki_header, $this->tpl);
		ob_end_clean();
	}
	
	function wiki_footer() {
		global $db;
		
		ob_end_clean();
		ob_start();
		include WIKI_ROOT . "/includes/interface/footer.php";
		$this->wiki_footer = trim(ob_get_contents());
		$this->tpl = str_replace('<wiki_footer>', $this->wiki_footer, $this->tpl);
		ob_end_clean();
	}
	
	function content() {
		global $db;
		
		ob_start();
		echo $this->content;
		$this->content = trim(ob_get_contents());
		$this->tpl = str_replace('<content>', $this->content, $this->tpl);
		ob_end_clean();
	}
	
	function generate($customfooter) {
		$this->assets();
		$this->metadata();
		$this->title();
		$this->ete_header();
		$this->ete_notifications();
		$this->ete_sidebar();
		$this->ete_footer();
		$this->wiki_header();
		//if($customfooter) {
			//$this->wiki_footer();
		//}		
		$this->content();
		
		echo $this->tpl;
		$this->generated = true;
	}
}
