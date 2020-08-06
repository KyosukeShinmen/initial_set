<?php
require dirname(__FILE__)."/config.php";

class PHPDOM
{
    function __construct(){
        $this->doc = new DOMDocument('1.0', 'UTF-8');
    }

    function setNode(string $tagName, ?string $className = NULL, ?string $text = NULL): object
    {
        $node = $this->doc->createElement($tagName, $text);
        if(!empty($className)) $node->setAttribute("class", $className);

        return $node;
    }

    function a(string $href, ?string $className = NULL, ?string $text = NULL): object
    {
        $node = $this->doc->createElement("a", $text);
        if(!empty($className)) $node->setAttribute("class", $className);
        $node->setAttribute("href", $href);

        return $node;
    }

    function img(string $src, ?string $className = NULL): object
    {
        $node = $this->doc->createElement("img");
        if(!empty($className)) $node->setAttribute("class", $className);
        $node->setAttribute("src", $src);
        $node->setAttribute("loading", "lazy");

        return $node;
    }

    function generator(object $obj):void
    {
        $this->doc->appendChild($obj);
        echo $this->doc->saveHTML();
    }
}

class breadcrumb extends PHPDOM
{
    function __construct(){
        parent::__construct();
        $this->json = [
            '@context'          => 'https://schema.org',
            '@type'             => 'BreadcrumbList',
            'itemListElement'   => []
        ];

        $this->class = "breadcrumb";
        
        $this->list = $this->setNode("ul", "{$this->class}__list");

        $this->position = 1;
    }

    function append(string $name, string $link){
        array_push($this->json['itemListElement'],[
            '@type'     => 'ListItem',
            'position'  => $this->position,
            'name'      => $name,
            'item'      => $link
        ]);

        $item = $this->setNode("li", "{$this->class}__item");
        $item->appendChild($this->a($link, "{$this->class}__link", $name));
        $this->list->appendChild($item);

        $this->position++;
    }

    function build(){
        $wrap = $this->setNode("section", $this->class);
        $wrap->appendChild($this->list);
        $this->generator($wrap);
        echo '<script type="application/ld+json">';
        echo json_encode($this->json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        echo '</script>';
    }
}


class Meta
{
    public function setName(?string $str)
    {
        $this->name = $str;
    }

    public function setURL(?string $str)
    {
        $this->url = $str;
    }

    public function setTitle(?string $str)
    {
        $this->title = $str;
    }

    public function setCode(?array $array)
    {
        $this->code = $array;
    }

    public function setParent(?string $code)
    {
        $this->parent = $code;
    }
}

function loadDescendantObject(?string $exclude = null)
{
    $params  = PARAM;
    if (!empty($exclude)) {
        $num = array_search($exclude, $params);
        array_splice($params, $num);
    }
    foreach (array_reverse($params) as $param) {
        $func = "__{$param}";
        if (function_exists($func) && !empty($func()->name)) {
            return $func();
        }
    }
    $meta = new Meta;
    $meta->setName("一覧");
    $meta->setTitle("一覧");
    $meta->setURL("{$_(DOMAIN)}{$_(findDir())}/");
    $meta->setCode([findDir()]);

    return $meta;
}

?>