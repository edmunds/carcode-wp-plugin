<?php
/*
 * Plugin Name: CarCode WordPress Plugin
 * Plugin URI: https://github.com/abrahamcuenca/carcode-wp-plugin
 * Description: This plugin allows you to add a CarCode widget on to your WordPress site. This plugin does not allow you to display the CarCode widget only on certain pages such as VDP or SRP. In order to add the CarCode widget to certain pages add a custom block and paste in the widget code provided by the Widgets Implementation team.
 * Version: 1.3.0
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

if (!defined("ABSPATH")) {
    exit;
}

add_action("admin_menu", "carcode_wp_plugin_add_admin_menu", 1);
add_action("admin_init", "carcode_wp_plugin_settings_init", 2);

function carcode_wp_plugin_add_admin_menu()
{
    add_menu_page(
        "CarCode Widget",
        "CarCode Widget Config",
        "manage_options",
        "carcode_wp_plugin",
        "carcode_wp_plugin_options_page",
        "dashicons-car",
        20
    );
}

function carcode_wp_plugin_settings_init()
{
    register_setting("carcode_wp_plugin_page", "carcode_wp_plugin_settings", "carcode_wp_plugin_validate");

    add_settings_section(
        "carcode_wp_plugin_page_section",
        "CarCode WordPress Plugin",
        "carcode_wp_plugin_settings_section_callback",
        "carcode_wp_plugin_page"
    );

    add_settings_field(
        "carcode_wp_plugin_widget_type",
        "CarCode Widget Type",
        "carcode_wp_plugin_widget_type_render",
        "carcode_wp_plugin_page",
        "carcode_wp_plugin_page_section"
    );

    add_settings_field(
        "carcode_wp_plugin_carcode_id",
        "CarCode ID",
        "carcode_wp_plugin_carcode_id_render",
        "carcode_wp_plugin_page",
        "carcode_wp_plugin_page_section"
    );

    add_settings_field(
        "carcode_wp_plugin_eas_id",
        "Content-Container (EAS) ID",
        "carcode_wp_plugin_eas_id_render",
        "carcode_wp_plugin_page",
        "carcode_wp_plugin_page_section"
    );

    add_settings_field(
        "carcode_wp_plugin_skip_button_mobile_id",
        "Skip button on mobile devices",
        "carcode_wp_plugin_skip_button_mobile_id_render",
        "carcode_wp_plugin_page",
        "carcode_wp_plugin_page_section"
    );
}

function carcode_wp_plugin_validate($input)
{
    $validated = array();

    // Set defaults for all settings
    $validated['carcode_wp_plugin_widget_type'] = isset($input['carcode_wp_plugin_widget_type']) ? wp_filter_nohtml_kses($input['carcode_wp_plugin_widget_type']) : '';
    $validated['carcode_wp_plugin_carcode_id'] = isset($input['carcode_wp_plugin_carcode_id']) ? wp_filter_nohtml_kses($input['carcode_wp_plugin_carcode_id']) : '';
    $validated['carcode_wp_plugin_eas_id'] = isset($input['carcode_wp_plugin_eas_id']) ? wp_filter_nohtml_kses($input['carcode_wp_plugin_eas_id']) : '';
    $validated['carcode_wp_plugin_skip_button_mobile_id'] = isset($input['carcode_wp_plugin_skip_button_mobile_id']) ? '1' : '0';

    return $validated;
}

function carcode_wp_plugin_widget_type_render()
{
    $options = get_option("carcode_wp_plugin_settings");
    $widget_type_option = $options["carcode_wp_plugin_widget_type"];
    $radio_group_name = "carcode_wp_plugin_settings[carcode_wp_plugin_widget_type]";
    ?>
    <input id="id_widget_type" type="radio" name="<?= $radio_group_name; ?>"
           value="dealerId" <?= checked("dealerId", $widget_type_option) ?>>
    <label for="id_widget_type">Dealer Id</label><br/>
    <input id="slug_widget_type" type="radio" name="<?= $radio_group_name; ?>"
           value="slugId" <?= checked("slugId", $widget_type_option) ?>>
    <label for="slug_widget_type">Widget Slug Id</label>
    <?php
}

function carcode_wp_plugin_eas_id_render()
{
    $options = get_option("carcode_wp_plugin_settings");
    $eas_id_setting = sanitize_text_field($options["carcode_wp_plugin_eas_id"]);
    ?>
    <input type="text" name="carcode_wp_plugin_settings[carcode_wp_plugin_eas_id]" value="<?= $eas_id_setting; ?>">
    <span>(leave blank to disable)</span>
    <?php
}


function carcode_wp_plugin_carcode_id_render()
{
    $options = get_option("carcode_wp_plugin_settings");
    $carcode_id_setting = sanitize_text_field($options["carcode_wp_plugin_carcode_id"]);
    ?>
    <input type="text" name="carcode_wp_plugin_settings[carcode_wp_plugin_carcode_id]"
           value="<?= $carcode_id_setting; ?>">
    <span>(leave blank to disable)</span>
    <?php
}

function carcode_wp_plugin_skip_button_mobile_id_render()
{
    $options = get_option("carcode_wp_plugin_settings");
    $skip_button_mobile_setting = $options["carcode_wp_plugin_skip_button_mobile_id"];
    ?>
    <input
            type="checkbox"
            name="carcode_wp_plugin_settings[carcode_wp_plugin_skip_button_mobile_id]"
        <?php checked($skip_button_mobile_setting, '1'); ?>
    >
    <span>(The widget will be hidden on mobile screens but will still display on desktop)</span>
    <span><?= $skip_button_mobile_setting ?></span>
    <?php
}


function carcode_wp_plugin_settings_section_callback()
{
    echo <<<EOT
<p>Thank you for using the CarCode WordPress Plugin. This plugin allows you to add the CarCode widget script<br/>
to your wordpress site as well as the Content-Container script.</p>

<h3>How to get Started</h3>
<p>In order to enable the CarCode widget or Content-Container scripts you should have received some Ids<br/>
from an Edmunds Implementation Specialist.
<em>This plugin adds the Script(s) to every page on a WordPress website.</em><br/>
To add the widget on specific pages (SRP, VDP, etc) modify your site's theme files (e.g. "header.php")<br/>
and add the following snippet to your files replacing <code>PLACEHOLDER</code> with the provided Id(s)</p>

<h3>For CarCode, how do I know if I have been provided a Dealer Id or a widget Slug Id?</h3>
Typically a Slug will be alphanumeric e.g. <code>a1b2c3</code>.<br/>
A Dealer Id will be numeric e.g. <code>123456</code>.<br/>


<h3>Adding the CarCode Widget snippet manually:</h3>
<p>Typically a Content-Container (EAS) Id will be a numeric value e.g. <code>123456</code></p>

<strong>Slug Id Snippet</strong><br/>
<pre>
 &lt;script src="https://www.carcodesms.com/widgets/s/PLACEHOLDER.js" type="text/javascript" async defer &gt;&lt;/script&gt;
</pre><br/>

<strong>Dealer ID Snippet</strong><br/>
<pre>
 &lt;script src="https://www.carcodesms.com/widgets/PLACEHOLDER.js" type="text/javascript" async defer &gt;&lt;/script&gt;
</pre><br/>

<p>For configuring the widget through the SDK review the <br/>
<a herf="https://github.com/CarcodeSMS/cc-sdk-documentation">CarCode SDK Documentation</a>
</p>

<h3>Adding the Content-Container (EAS) script manually:</h3>
<strong>content-container (EAS) script</strong><br/>
<pre>
 &lt;script src="https://content-container.edmunds.com/PLACEHOLDER.js" type="text/javascript" async defer &gt;&lt;/script&gt;
</pre><br/>
<hr/>
<h2>Plugin Configuration:</h2>
<p>
To enable the CarCode widget select the widget id type (Dealer Id or Widget Slug Id) and enter the provided id in the CarCode ID text box.<br/>
To disable the CarCode widget leave the CarCode ID text box blank.</p>
<p>
To enable the Content-Container (EAS) script add the provided Content-Container ID in the EAS ID text box.<br/>
To disable the Content-Container (EAS) script leave the EAS ID text box blank.
</p>
<p>
To hide the widget on mobile check the 'Skip button on Mobile' option.
<em>This only affects the mobile widget and does not affect the desktop widget.</em>
</p>
EOT;
}

function carcode_wp_plugin_options_page()
{
    ?>
    <form action="options.php" method="post">
        <?php
        settings_fields("carcode_wp_plugin_page");
        do_settings_sections("carcode_wp_plugin_page");
        submit_button();
        ?>
    </form>
    <?php
}

function carcode_wp_plugin_build_carcode_url($type)
{
    $url = "https://www.carcodesms.com/widgets/";

    if ($type == "slug") {
        $url .= "s/";
    }

    return $url;
}

function carcode_wp_plugin_info()
{
    $options = get_option("carcode_wp_plugin_settings");
    $eas_id_setting = $options["carcode_wp_plugin_eas_id"];
    $widget_type_setting = $options["carcode_wp_plugin_widget_type"];
    $carcode_id_setting = $options["carcode_wp_plugin_carcode_id"];
    $skip_button_mobile_setting = isset($options["carcode_wp_plugin_skip_button_mobile_id"]) ? $options["carcode_wp_plugin_skip_button_mobile_id"] : '0';
    ?>
    <script type="text/javascript">
        window.__carcode_wp_plugin = {
            version: 'v1.3.0',
            easId: '<?= isset($eas_id_setting) && !empty($eas_id_setting) ? esc_js($eas_id_setting) : "No EAS id set" ?>',
            widgetType: '<?= isset($widget_type_setting) && !empty($widget_type_setting) ? esc_js($widget_type_setting) : "No widget type set" ?>',
            carcodeId: '<?= isset($carcode_id_setting) && !empty($carcode_id_setting) ? esc_js($carcode_id_setting) : "No CarCode id set" ?>',
            skipButtonMobile: '<?= $skip_button_mobile_setting === '1' ? "yes" : "no" ?>',
        };

    window.__carcode = {
        <?php if ($skip_button_mobile_setting === '1') { ?>  
            skipButton: <?= wp_is_mobile() ? 'true' : 'false'; ?>,
        <?php } ?>
    };

    </script>
    <?php
}

function carcode_wp_plugin_add_eas_js_snippet()
{
    $options = get_option("carcode_wp_plugin_settings");
    $eas_id_setting = sanitize_text_field($options["carcode_wp_plugin_eas_id"]);
    if (isset($eas_id_setting) && !empty($eas_id_setting)) {
        ?>
        <script src="https://content-container.edmunds.com/<?= $eas_id_setting; ?>.js" type="text/javascript" async
                defer</script>
        <?php
    }
}

function carcode_wp_plugin_add_carcode_js_snippet()
{
    $options = get_option("carcode_wp_plugin_settings");
    $carcode_id_setting = sanitize_text_field($options["carcode_wp_plugin_carcode_id"]);
    $widget_type_setting = $options["carcode_wp_plugin_widget_type"];

    $url = carcode_wp_plugin_build_carcode_url($widget_type_setting);


    if (isset($carcode_id_setting) && !empty($carcode_id_setting)) {
        ?>
        <script src="<?= $url . $carcode_id_setting; ?>.js" type="text/javascript" async defer></script>
        <?php
    }
}

add_action("wp_head", "carcode_wp_plugin_add_carcode_js_snippet", 3);
add_action("wp_head", "carcode_wp_plugin_add_eas_js_snippet", 4);
add_action("wp_head", "carcode_wp_plugin_info", 10);
