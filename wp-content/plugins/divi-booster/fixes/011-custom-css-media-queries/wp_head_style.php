<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

<?php 
include_once(dirname(__FILE__).'/media_queries.php');
$wtfdivi011_media_queries = wtfdivi011_media_queries();

if (isset($option['customcss'])) {
	
	// Output each enabled CSS block	
	foreach(wtfdivi011_html_checkbox_vals($option['customcss']['enabled']) as $k=>$enabled) {
		if ($k==0) { continue; } // ignore template block
		
		
		if ($enabled) {
			
			// === build the media query === //
			$media_query = ($option['customcss']['mediaqueries'][$k]=='all')?'':$wtfdivi011_media_queries[$option['customcss']['mediaqueries'][$k]]['css'];
			
			// === build the selector === //
			
			// apply the body classes
			$selector = 'body';
			foreach (array('user', 'device', 'browser', 'pagetype', 'elegantthemes') as $selection) {
				$selector.= ($option['customcss'][$selection][$k]=='all')?'':'.'.$option['customcss'][$selection][$k];
			}
			
			// === build the CSS === //
			$css = trim($option['customcss']['css'][$k]);
			$css = $this->minify_css($css);
			$css_rules = array_filter(explode("}", $css)); // break into individual css rules
			
			foreach ($css_rules as $id=>$rule) {
			
				// get selectors for the rule
				list($rule_selectors, $rule_css) = explode('{', $rule); // get the selector list
				$rule_selectors = explode(',', $rule_selectors); // split them up
			
				// add the base selector to each selector
				foreach($rule_selectors as $j=>$rule_selector) {
					$rule_selectors[$j] = $selector.' '.$rule_selector;
				}
				
				$final_selectors = implode(",\n",$rule_selectors);
				
				$css_rules[$id] = $final_selectors.' { '.trim($rule_css).' }'; // add base selector to first selector
			}
			
			// === output the CSS === //
			$css = implode("\n", $css_rules);
			echo (empty($media_query))?$css:"$media_query {\n$css\n}";
			
		}
	}
}
?>