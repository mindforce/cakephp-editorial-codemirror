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
        'block' => 'false',
		'options' => [
			'mode' => 'xml',
			'theme' => 'eclipse',
			'indentUnit' => 4,
			'keymap' => 'sublime',
			'lineNumbers' => true
		]
	];

	public function assets($block = true){
		$assets = '';
        $assets .= $this->Html->css('Editorial/Codemirror.codemirror.css', ['block' => $block]);
		$codemirrorTheme = $this->config('options.theme');
		if(!empty($codemirrorTheme)) {
			$assets .= $this->Html->css('Editorial/Codemirror.theme/'.$codemirrorTheme.'.css', ['block' => $block]);
		}
		$assets .= $this->Html->script('Editorial/Codemirror.codemirror.js', ['block' => $block]);
		$codemirrorMode = $this->config('options.mode');
		if(!empty($codemirrorTheme)) {
			$assets .= $this->Html->script('Editorial/Codemirror.mode/'.$codemirrorMode.'/'.$codemirrorMode.'.js', ['block' => $block]);
		}
		$codemirrorKeymap = $this->config('options.keymap');
		if(!empty($codemirrorKeymap)) {
			$assets .= $this->Html->script('Editorial/Codemirror.keymap/'.$codemirrorKeymap.'.js', ['block' => $block]);
		}
		$assets .= $this->Html->script('Editorial/Codemirror.formatting.min.js', ['block' => $block]);
        if(!$block){
            return $assets;
        }
	}

	public function connect($content = null, $block = true){
		if(empty($content)) {
			return;
		}
		$searchRegex = '/(<textarea.*class\=\".*'
			.Configure::read('Editorial.class').'\"[^>]*>.*<\/textarea>)/isU';
		$js = '';
		if(preg_match_all($searchRegex, $content, $matches)){
			$editorOptions = json_encode($this->config('options'));
			foreach ($matches[0] as $input){
				if(preg_match('/<textarea.*id\=\"(.*)\"[^>]*>.*<\/textarea>/isU', $input, $idMatches)) {
					$js .= "\tCodeMirror.fromTextArea(document.getElementById('".$idMatches[1]."'), ".$editorOptions.");\n";
				}
			}
            if($this->request->is('ajax')){
                $js = "setTimeout(function() { ".$js." }, 200)";
            } else {
                $js = "window.onload = function() {\n".$js."};\n";
            }
		}
		if(!empty($js)){
			return $this->Html->scriptBlock($js, ['block' => $block]);
		}
        return;
	}
}
