<?php

namespace BaseReality\Content;


class BaseRealityEvent{

	//The values should be valid CSS class names
	//e.g. -?[_a-zA-Z]+[_a-zA-Z0-9-]*
	public static $previewContent =  'basereality_previewContent';
	public static $previewContentByID =  'basereality_previewContentByID';
	public static $closePreview =  'basereality_closePreview';

//	public static $showContentWithoutTags = 'basereality_showContentWithoutTags';
	public static $filterContentByTags = 'basereality_filterContentByTags';

	public static $nextPage = 	'basereality_nextPage';
	public static $previousPage =  'basereality_previousPage';
	public static $goToPage =  "basereality_goToPage";
	public static $firstPage =  "basereality_firstPage";
	public static $lastPage =  "basereality_lastPage";

	public static $addControl =  'basereality_addControl';

	public static $logLevelChange =  'basereality_logLevelChange';

	public static $toggleControlPanelVisibility =  'basereality_toggleControlPanelVisibility';
	public static $loginRedirect =  'basereality_loginRedirect';
	public static $logoutRedirect = 'basereality_logoutRedirect';

	public static $decrement =  'basereality_decrement';
	public static $increment =  'basereality_increment';

	public static $cssColorFocus = 'basereality_cssColorFocus';
	public static $cssColorChanged = 'basereality_cssColorChanged';
	public static $cssColorChangedHSL = 'basereality_cssColorChangedHSL';

	public static $cssSizeUpdate = 'basereality_cssSizeUpdate';
	public static $cssSizeChanged = 'basereality_cssSizeChanged';

	public static $updateCSS = 'basereality_updateCSS';

	public static $logFilterUpdate = 'basereality_logFilterUpdate';
	public static $logViewUpdate = 'basereality_logViewUpdate';

	public static $addTagToContent		= 'basereality_addTagToContent';
	public static $removeTagFromContent = 'basereality_removeTagFromContent';

	public static $addTagToFilter = 'basereality_addTagToFilter';
	public static $removeTagFromFilter = 'basereality_removeTagFromFilter';

	public static $contentFilterChanged = 'basereality_contentFilterChanged';

	//Sets the number of content pages avaiable, and which page we're on.
	//public static $setContentPages = 'basereality_setContentPages';


	public static $dragStart = 'basereality_dragStart';
	public static $dragStop = 'basereality_dragStop';
}