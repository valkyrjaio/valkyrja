<?php

/* layout.twig */
class __TwigTemplate_040e91df8f155a16900cdb7c58aa0531aee363cd7a90fd48b892bb1fadb33389 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
    <head>
        <title>A test application</title>
    </head>
    <body>
        <header>A header</header>

        <main>
            ";
        // line 9
        $this->displayBlock('content', $context, $blocks);
        // line 10
        echo "        </main>

        <footer>A footer</footer>
    </body>
</html>

";
    }

    // line 9
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function getDebugInfo()
    {
        return array (  42 => 9,  32 => 10,  30 => 9,  20 => 1,);
    }

    public function getSource()
    {
        return "<html>
    <head>
        <title>A test application</title>
    </head>
    <body>
        <header>A header</header>

        <main>
            {% block content %}{% endblock %}
        </main>

        <footer>A footer</footer>
    </body>
</html>

";
    }
}
