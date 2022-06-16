<?php
/*
 * Plugin Name: CarCode WordPress Plugin
 * Plugin URI: https://github.com/abrahamcuenca/carcode-wp-plugin
 * Description: This plugin allows you to add a CarCode widget on to your WordPress site. This plugin does not allow you to display the CarCode widget only on certain pages such as VDP or SRP. In order to add the CarCode widget to certain pages add a custom block and paste in the widget code provided by the Widgets Implementation team.
 * Version: 1.0.0
 * requires at least: 5.2
 * Requires PHP: 7.1
 * Author: Abraham Cuenca
 * Author URI: https://abrahamcuenca.com
 * License: Apache-2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Update URI: https://github.com/abrahamcuenca/carcode-wp-plugin
 */
/*
   Copyright 2022 Abraham Cuenca

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License. 
 */

if (!defined('ABSPATH')){
    exit;
}

add_action('admin_menu', 'carcode_wp_plugin_add_admin_menu');
add_action('admin_init', 'carcode_wp_plugin_settings_init');

function carcode_wp_plugin_add_admin_menu() {
    add_menu_page(
        'CarCode Widget',
        'CarCode Widget Config',
        'manage_options',
        'carcode_wp_plugin',
        'carcode_wp_plugin_options_page',
    );
}

function carcode_wp_plugin_settings_init(){
    register_setting('carcode_wp_plugin_page', 'carcode_wp_plugin_settings', 'carcode_wp_plugin_validate');

    add_settings_section(
        'carcode_wp_plugin_page_section',
        'CarCode Widget',
        'carcode_wp_plugin_settings_section_callback',
        'carcode_wp_plugin_page'
    );

    add_settings_field(
        'carcode_wp_plugin_widget_type',
        'CarCode Widget Type',
        'carcode_wp_plugin_widget_type_render',
        'carcode_wp_plugin_page',
        'carcode_wp_plugin_page_section'
    );

    add_settings_field(
        'carcode_wp_plugin_widget_slug',
        'ID',
        'carcode_wp_plugin_widget_slug_render',
        'carcode_wp_plugin_page',
        'carcode_wp_plugin_page_section'
    );
}

function carcode_wp_plugin_validate($input) {
    return array_map('wp_filter_nohtml_kses', (array)$input);
}

function carcode_wp_plugin_widget_type_render() {
    $options = get_option('carcode_wp_plugin_settings');
    ?>
    <input id="id_widget_type" type="radio" name="carcode_wp_plugin_settings[carcode_wp_plugin_type]" value="id" <?php checked('id', $options['carcode_wp_plugin_type']) ?>>
    <label for="id_widget_type">Dealer Id</label><br/>
    <input id="slug_widget_type" type="radio" name="carcode_wp_plugin_settings[carcode_wp_plugin_type]" value="slug" <?php checked('slug', $options['carcode_wp_plugin_type']) ?>>
    <label for="slug_widget_type">Widget Slug Id</label>
    <?php
}

function carcode_wp_plugin_widget_slug_render() {
    $options = get_option('carcode_wp_plugin_settings');
    ?>
    <input type="text" name="carcode_wp_plugin_settings[carcode_wp_plugin_widget_slug]" value="<?php echo sanitize_text_field($options['carcode_wp_plugin_widget_slug']);?>">
    <?php
}

function carcode_wp_plugin_settings_section_callback() {
    echo <<<EOT
<p>The CarCode Widget slug ID is a value that is provided by the Widgets Implementation team.<br/>
This plugin adds the CarCode widget to every page on a WordPress website.</p>

<p>To add the widget on specific pages (SRP, VDP, etc) modify the theme and add the following snippet to your files replacing <code>[id]</code> with your Slug or dealer Id.</p>

<h3>How do I know if I have been provided a Dealer Id or a widget Slug?</h3>
Typically a Slug will be alphanumeric e.g. a1b2c3.
A Dealer Id will be numeric e.g. 123456


<strong>Slug Snippet</strong>
<pre>
 &lt;script src='https://www.carcodesms.com/widgets/s/[slug].js' type='text/javascript' async defer &gt;&lt;/script&gt;
</pre>

<strong>Dealer ID Snippet</strong>
<pre>
 &lt;script src='https://www.carcodesms.com/widgets/[id].js' type='text/javascript' async defer &gt;&lt;/script&gt;
</pre>

<p>For configuring the widget through the SDK review the <br/>
<a herf="https://github.com/CarcodeSMS/cc-sdk-documentation">CarCode SDK Documentation</a>
</p>
EOT;
}

function carcode_wp_plugin_options_page() {
    ?>
    <form action="options.php" method="post">
        <?php
        settings_fields('carcode_wp_plugin_page');
        do_settings_sections('carcode_wp_plugin_page');
        submit_button();
        ?>
    </form>
    <?php
}

add_action('wp_head', 'carcode_wp_plugin_add_js_snippet');

function carcode_wp_plugin_build_url($type) {
    $url = "https://www.carcodesms.com/widgets/";

    if ($type == 'slug') {
        $url .= 's/';
    }

    return $url;
}

function carcode_wp_plugin_add_js_snippet() {
    $options = get_option('carcode_wp_plugin_settings');
    $widget_slug = sanitize_text_field($options['carcode_wp_plugin_widget_slug']);
    $widget_type = $options['carcode_wp_plugin_type'];

    $url = carcode_wp_plugin_build_url($widget_type);


    if (!is_null($widget_slug)) {
        ?>
        <script
                src='<?php echo $url . $widget_slug ?>.js'
                type='text/javascript'
                async
                defer
        ></script>
        <?php
    }
}
