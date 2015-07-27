<?php


namespace Intahwebz\Utils;

abstract class ScriptInclude
{
    private $useCDNForScripts = true;

    protected $scriptVersion;

    protected $includeJSArray = array();

    /**
     * @var CSSFile[]
     */
    protected $cssFiles = [];

    private $onBodyLoadJavascript = array();

    /**
     * @var bool
     */
    private $showJSErrors;

    /**
     * @param $packScripts
     * @param $useCDNForScripts
     * @param \Intahwebz\Domain $domain
     * @param $liveServer
     * @param $siteScriptVersion
     */
    public function __construct()
    {
        $this->useCDNForScripts = false;//$useCDNForScripts->getBool();
        $this->showJSErrors = true;//$showJSErrors->getBool();
        $this->scriptVersion = '1.2.3';//$siteScriptVersion->getString();
    }

    abstract public function includeCSS();
    abstract public function emitJSRequired();

    
    /**
     * @param \Intahwebz\DisplayableContent $object
     */
    public function addBodyLoadFunctionBindData(\Intahwebz\DisplayableContent $object)
    {
        $ID = $object->getDOMID();
        $jsonString = json_encode_object($object->getDisplayableVersion());
        $jsonString = addslashes($jsonString);
        $dataBindingJS = "$('#".$ID."').data('serialized', '".$jsonString."');\n";
        $this->addBodyLoadFunction($dataBindingJS);
    }

    /**
     * @param $dataBindingJS
     */
    public function addBodyLoadFunction($dataBindingJS)
    {
        $this->onBodyLoadJavascript[] = $dataBindingJS;
    }

    /**
     * @param $cssFile
     * @param string $media
     */
    public function addCSS($cssFile, $media = 'screen')
    {
        $this->cssFiles[] = new CSSFile($cssFile, $media);
    }


    /**
     * @param $includeJS
     */
    public function addJSRequired($includeJS)
    {
        $this->includeJSArray[] = $includeJS;
    }

    /**
     * @return string
     */
    public function emitOnBodyLoadJavascript()
    {
        $output = "";
        $output .= "<script type='text/javascript'>";
        $output .= "try {\n";

        foreach ($this->onBodyLoadJavascript as $functionToPerform) {
            $output .= $functionToPerform."\n";
        }
        $output .= "//hey we've loaded";
        $output .= "\n } catch (error) { ";
        $output .= " alert('Error caught: ' + error)";
        $output .= " }";
        $output .= "</script>";

        return $output;
    }

    /**
     * @param $name
     * @param $object
     */
    public function addBodyLoadObjectInit($name, $object)
    {
        $jsonString = json_encode_object($object);
        $jsString = "window.$name = json_decode_object('".addslashes($jsonString)."');\n";
        $this->addBodyLoadFunction($jsString);
    }
}
