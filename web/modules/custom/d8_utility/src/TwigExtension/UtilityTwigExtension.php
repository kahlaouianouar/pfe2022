<?php

namespace Drupal\d8_utility\TwigExtension;

use Drupal\Core\Template\TwigExtension;
use Drupal\image\Entity\ImageStyle;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Block\TitleBlockPluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Render\Element;

/**
 *
 */
class UtilityTwigExtension extends TwigExtension
{
    /**
     * Gets a unique identifier for this Twig extension.
     */
    public function getName()
    {
        return "d8_utility.twig.extension";
    }

    /**
     * Generates a list of all Twig filters that this extension defines.
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('custom_replace', array($this, 'custom_replace')),
            new \Twig_SimpleFilter('truncate', array($this, 'truncate')),
        );
    }

    /**
     * Generates a list of all Twig functions that this extension defines.
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("display_msg", array($this, "display_msg")),
            new \Twig_SimpleFunction("is_front", array($this, "is_front")),
            new \Twig_SimpleFunction("linkExterne", array($this, "linkExterne")),
            new \Twig_SimpleFunction("_pre", array($this, "_pre")),
            new \Twig_SimpleFunction("twig_file_create_url", array($this, "twig_file_create_url")),
            new \Twig_SimpleFunction("image_style", array($this, "image_style")),
            new \Twig_SimpleFunction("load_file_by_target_id", array($this, "load_file_by_target_id")),
            new \Twig_SimpleFunction("load_term_by_target_id", array($this, "load_term_by_target_id")),
            new \Twig_SimpleFunction("load_file_fond_1_full", array($this, "load_file_fond_1_full")),
            new \Twig_SimpleFunction("load_paragraph_by_target_id", array($this, "load_paragraph_by_target_id")),
            new \Twig_SimpleFunction("node_load", array($this, "node_load")),
            new \Twig_SimpleFunction("isMobile", array($this, "isMobile")),
            new \Twig_SimpleFunction("is_numeric", array($this, "is_numeric")),
            new \Twig_SimpleFunction("is_url_external", array($this, "is_url_external")),
            new \Twig_SimpleFunction("get_formation_summary_by_position_twig", array($this, "get_formation_summary_by_position_twig")),
            new \Twig_SimpleFunction('drupal_view', 'views_embed_view'),
            new \Twig_SimpleFunction('drupal_entity', [$this, 'drupalEntity']),
            new \Twig_SimpleFunction('drupal_block', [$this, 'drupalBlock']),
            new \Twig_SimpleFunction('drupal_config', [$this, 'drupalConfig']),
            new \Twig_SimpleFunction('drupal_field', [$this, 'drupalField']),
            new \Twig_SimpleFunction('drupal_view_result', 'views_get_view_result'),
            new \Twig_SimpleFunction('load_tree_term', array($this, "load_tree_term")),
            new \Twig_SimpleFunction('curl_content', array($this, "curl_content")),
        );
    }
    public function curl_content($user_id_post_id) {

        return false;
    }
    public function load_paragraph_by_target_id($id) {
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $pg = Paragraph::load($id);
        if ($pg){
            if($pg->hasTranslation($languageId)){
                $pg = \Drupal::service('entity.repository')->getTranslationFromContext($pg, $languageId);
            }
        }
        return $pg;
    }
    public function is_url_external($uri) {
        return \Drupal\Component\Utility\UrlHelper::isExternal($uri);
    }
    public function is_numeric($key) {
        return is_numeric($key);
    }
    public function isMobile() {
        $detect = new \Mobile_Detect;
        if( $detect->isMobile() && !$detect->isTablet() ){
            return true;
        }
        return false;
    }
    public function node_load($nid){
        if((int) $nid == 0){
            return null;
        }
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $node = Node::load($nid);
        if ($node){
            if($node->hasTranslation($languageId)){
                $node = \Drupal::service('entity.repository')->getTranslationFromContext($node, $languageId);
            }
            return $node;
        }
        return false;
    }
    public function load_file_fond_1_full($target_id, $thumbnail){
        $file = File::load($target_id);
        $MimeType = $file->getMimeType();
        $uri = $file->uri->value;

        if ($MimeType === 'video/mp4'){
            return array('type'=>'video', 'src' => file_create_url($uri));
        }else{
            return array('type'=>'image', 'src' => ImageStyle::load($thumbnail)->buildUrl($uri));
        }
    }
    public function is_front(){
        $is_front = \Drupal::service('path.matcher')->isFrontPage();
        return $is_front;
    }
    public function display_msg(){
        if (isset($_GET['msg'])){
            print '<div data-drupal-messages=""><div role="contentinfo" class="messages messages--status">Nous avons bien enregistré vos réponses.</div></div>';
        }
    }
    public function linkExterne($url){
        $pos = strpos($url, 'http');
        if ($pos !== false){
            return true;
        }
        return false;
    }
    public function _pre($value)
    {
        echo '<pre>';
        dump($value);
        echo '</pre>';
        //die('end');
    }
    public function twig_file_create_url($uri, $fileName)
    {
        return file_create_url($uri.$fileName);
    }

    /**
     * Generate images styles for given image
     */
    public function image_style($uri, $thumbnail)
    {
        $imageStyle = ImageStyle::load($thumbnail);
        if ($imageStyle){
            return$imageStyle->buildUrl($uri);
        }
    }
    public function load_file_by_target_id($target_id)
    {
        $file = File::load($target_id);
        if ($file){
            $uri = $file->uri->value;
            return file_create_url($uri);
        }
    }
    public function load_term_by_target_id($target_id)
    {
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $term = Term::load($target_id);

        if ($term){
            if($term->hasTranslation($languageId)){
                $term = \Drupal::service('entity.repository')->getTranslationFromContext($term, $languageId);
            }
            return $term;
        }
    }
    public static function custom_replace ( $subject, $search , $replace )
    {
        $str = str_replace($search, $replace, $subject);
        return $str;
    }
    /**
     * Truncate the string.
     * @param $input
     * @param int $length
     * @param bool $ellipses
     * @param bool $strip_html
     * @return string
     */
    public static function truncate($input, $length = 50, $ellipses = false, $strip_html = true, $encoding = 'utf-8')
    {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (mb_strlen($input, $encoding) <= $length) {
            return $input;
        }

        //find last space within length
        //$last_space = mb_strrpos(mb_substr($input, 0, $length, $encoding), ' ', $encoding);

        $trimmed_text = mb_substr($input, 0, $length, $encoding);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= ' ...';
        }

        return $trimmed_text;
    }

    public function get_formation_summary_by_position_twig($node){

        if(empty($node)){
            return null;
        }

        return get_formation_summary_by_position($node, 'hide');
    }

    /**
     * Builds the render array for a block.
     *
     * In order to list all registered plugin IDs fetch them with block plugin
     * manager. With Drush it can be done like follows:
     * @code
     *   drush ev "print_r(array_keys(\Drupal::service('plugin.manager.block')->getDefinitions()));"
     * @endcode
     *
     * Examples:
     * @code
     *   # Print block using default configuration.
     *   {{ drupal_block('system_branding_block') }}
     *
     *   # Print block using custom configuration.
     *   {{ drupal_block('system_branding_block', {label: 'Branding', use_site_name: false})
     *
     *   # Bypass block.html.twig theming.
     *   {{ drupal_block('system_branding_block', wrapper=false) }}
     * @endcode
     *
     * @see https://www.drupal.org/node/2964457#block-plugin
     *
     * @param mixed $id
     *   The string of block plugin to render.
     * @param array $configuration
     *   (optional) Pass on any configuration to the plugin block.
     * @param bool $wrapper
     *   (optional) Whether or not use block template for rendering.
     *
     * @return null|array
     *   A render array for the block or NULL if the block cannot be rendered.
     */
    public function drupalBlock($id, array $configuration = [], $wrapper = TRUE) {

        $configuration += ['label_display' => BlockPluginInterface::BLOCK_LABEL_VISIBLE];

        /** @var \Drupal\Core\Block\BlockPluginInterface $block_plugin */
        $block_plugin = \Drupal::service('plugin.manager.block')
            ->createInstance($id, $configuration);

        // Inject runtime contexts.
        if ($block_plugin instanceof ContextAwarePluginInterface) {
            $contexts = \Drupal::service('context.repository')->getRuntimeContexts($block_plugin->getContextMapping());
            \Drupal::service('context.handler')->applyContextMapping($block_plugin, $contexts);
        }

        if (!$block_plugin->access(\Drupal::currentUser())) {
            return;
        }

        // Title block needs special treatment.
        if ($block_plugin instanceof TitleBlockPluginInterface) {
            $request = \Drupal::request();
            $route_match = \Drupal::routeMatch();
            $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
            $block_plugin->setTitle($title);
        }

        $build = [
            'content' => $block_plugin->build(),
            '#cache' => [
                'contexts' => $block_plugin->getCacheContexts(),
                'tags' => $block_plugin->getCacheTags(),
                'max-age' => $block_plugin->getCacheMaxAge(),
            ],
        ];

        if ($block_plugin instanceof TitleBlockPluginInterface) {
            $build['#cache']['contexts'][] = 'url';
        }

        if ($wrapper && !Element::isEmpty($build['content'])) {
            $build += [
                '#theme' => 'block',
                '#attributes' => [],
                '#contextual_links' => [],
                '#configuration' => $block_plugin->getConfiguration(),
                '#plugin_id' => $block_plugin->getPluginId(),
                '#base_plugin_id' => $block_plugin->getBaseId(),
                '#derivative_plugin_id' => $block_plugin->getDerivativeId(),
            ];
        }

        return $build;
    }

    public function drupalEntity($entity_type, $id = NULL, $view_mode = NULL, $langcode = NULL, $check_access = TRUE) {
        $entity_type_manager = \Drupal::entityTypeManager();
        if ($id) {
            $entity = $entity_type_manager->getStorage($entity_type)->load($id);
        }
        else {
            @trigger_error('Loading entities from route is deprecated in Twig Tweak 2.4 and will not be supported in Twig Tweak 3.0', E_USER_DEPRECATED);
            $entity = \Drupal::routeMatch()->getParameter($entity_type);
        }
        if ($entity && (!$check_access || $entity->access('view'))) {
            $render_controller = $entity_type_manager->getViewBuilder($entity_type);
            return $render_controller->view($entity, $view_mode, $langcode);
        }
    }

    public function drupalConfig($name, $key) {
        return \Drupal::config($name)->get($key);
    }

    /**
     * Returns the render array for a single entity field.
     *
     * Example:
     * @code
     *   {{ drupal_field('field_image', 'node', 1) }}
     *   {{ drupal_field('field_image', 'node', 1, 'teaser') }}
     *   {{ drupal_field('field_image', 'node', 1, {type: 'image_url', settings: {image_style: 'large'}}) }}
     * @endcode
     *
     * @param string $field_name
     *   The field name.
     * @param string $entity_type
     *   The entity type.
     * @param mixed $id
     *   The ID of the entity to render.
     * @param string $view_mode
     *   (optional) The view mode that should be used to render the field.
     * @param string $langcode
     *   (optional) Language code to load translation.
     * @param bool $check_access
     *   (optional) Indicates that access check is required.
     *
     * @return null|array
     *   A render array for the field or NULL if the value does not exist.
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
     */
    public function drupalField($field_name, $entity_type, $id = NULL, $view_mode = 'default', $langcode = NULL, $check_access = TRUE) {
        if ($id) {
            $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->load($id);
        }
        else {
            @trigger_error('Loading entities from route is deprecated in Twig Tweak 2.4 and will not be supported in Twig Tweak 3.0', E_USER_DEPRECATED);
            $entity = \Drupal::routeMatch()->getParameter($entity_type);
        }
        if ($entity && (!$check_access || $entity->access('view'))) {
            $entity = \Drupal::service('entity.repository')
                ->getTranslationFromContext($entity, $langcode);
            if (isset($entity->{$field_name})) {
                return $entity->{$field_name}->view($view_mode);
            }
        }
    }
    public function load_tree_term($target_id)
    {
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('services', $target_id, 1, true);

        if ($tree){
            foreach ($tree as $term){
                if($term->hasTranslation($languageId)){
                    $output[] = \Drupal::service('entity.repository')->getTranslationFromContext($term, $languageId);
                }else{
                    $output[] = $term;
                }
            }

            return $output;
        }
        return false;
    }

}
