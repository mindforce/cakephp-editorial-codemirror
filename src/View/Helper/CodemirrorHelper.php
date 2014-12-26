<?php
namespace Editorial\Codemirror\View\Helper;

use Editorial\Core\View\Helper\EditorialHelper;
use Cake\Core\Configure;


class CodemirrorHelper extends EditorialHelper {

/**
 * Default config for the helper.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'options' => [
			'mode' => 'xml',
			'theme' => 'eclipse',
			'indentUnit' => 4,
			'keymap' => 'sublime',
			'lineNumbers' => true
		]
	];

	public function initialize(){
		$this->Html->css('Editorial/Codemirror.codemirror.css', ['block' => true]);
		$codemirrorTheme = $this->config('options.theme');
		if(!empty($codemirrorTheme)) {
			$this->Html->css('Editorial/Codemirror.theme/'.$codemirrorTheme.'.css', ['block' => true]);
		}
		$this->Html->script('Editorial/Codemirror.codemirror.js', ['block' => true]);
		$codemirrorMode = $this->config('options.mode');
		if(!empty($codemirrorTheme)) {
			$this->Html->script('Editorial/Codemirror.mode/'.$codemirrorMode.'/'.$codemirrorMode.'.js', ['block' => true]);
		}
		$codemirrorKeymap = $this->config('options.keymap');
		if(!empty($codemirrorKeymap)) {
			$this->Html->script('Editorial/Codemirror.keymap/'.$codemirrorKeymap.'.js', ['block' => true]);
		}
		$this->Html->script('Editorial/Codemirror.formatting.min.js', ['block' => true]);
	}

	public function connect($content = null){
		if(empty($content)) {
			return;
		}
		$searchRegex = '/(<textarea.*class\=\".*'
			.Configure::read('Editorial.class').'\"[^>]*>.*<\/textarea>)/isU';
		$js = '';
		if(preg_match_all($searchRegex, $content, $matches)){
			$js .= "window.onload = function() {\n";
			$editorOptions = json_encode($this->config('options'));
			foreach ($matches[0] as $input){
				if(preg_match('/<textarea.*id\=\"(.*)\"[^>]*>.*<\/textarea>/isU', $input, $idMatches)) {
					$js .= "\tCodeMirror.fromTextArea(document.getElementById('".$idMatches[1]."'), ".$editorOptions.");\n";
				}
			}
			$js .= "};\n";
		}
		if(!empty($js)){
			$this->Html->scriptBlock($js, ['block' => true]);
		}
	}
}
