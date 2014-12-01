<?php

/* form.html.php */
class __TwigTemplate_bfa07abf5fbcf511e61391c996e91d1704090783dc2097464693163371c4d60f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div id=\"rules-field\">
  <div class=\"rules-repeater\">
    <div class=\"rules\">
      <label for=\"rule-context\">Rule Context</label>
      <select id=\"rule-context\" name=\"rules[][context]\" >
        <option value=\"--\">-Select-</option>
          ";
        // line 7
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["options"]) ? $context["options"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            // line 8
            echo "            <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "value", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "label", array()), "html", null, true);
            echo "</option>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "      </select>
      <div id=\"rule-context\">
        <label for=\"rule-value\">Rule Value</label>
        <input type=\"text\" name=\"rules[][value]\" value=\"\" />
      </div>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "form.html.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 10,  31 => 8,  27 => 7,  19 => 1,);
    }
}
