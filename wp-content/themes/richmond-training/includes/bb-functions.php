<?php
/**
 * Functions and hooks relating to Beaver Builder.
 */

/**
 * Filter to add custom button presets to various modules.
 *
 * @param object $form The module form object.
 * @param string $id   The module ID.
 *
 * @return object
 */
add_filter('fl_builder_register_settings_form', function ($form, $id) {
    if (! in_array($id, ['button', 'buttons_form', 'cta'], true)) {
        return $form;
    }

    // Button presets stuff.
    $presets = fs_get_bb_button_presets();
    if (empty($presets)) {
        return $form;
    }

    $preset_field_options = [
        'default'  => 'No Preset',
    ];
    foreach ($presets as $key => $preset) {
        $preset_field_options[$key] = $preset['name'];
    }

    $preset_field = [
        'button_preset' => [
            'type'    => 'select',
            'label'   => 'Button Preset',
            'default' => 'default',
            'options' => $preset_field_options,
        ],
    ];

    switch ($id) {
        case 'button':
            $form['style']['sections']['style']['fields'] = array_merge(
                $preset_field,
                $form['style']['sections']['style']['fields'],
            );
            break;
        case 'buttons_form':
            $form['tabs']['style']['sections']['style']['fields'] = array_merge(
                $preset_field,
                $form['tabs']['style']['sections']['style']['fields'],
            );
            break;
        case 'cta':
            $form['button']['sections']['btn_text']['fields'] = array_merge(
                $preset_field,
                $form['button']['sections']['btn_text']['fields'],
            );
            break;
    }

    return $form;
}, 10, 2);

/**
 * Sets custom row classes.
 *
 * @param object $form The module form object.
 * @param string $id   The module ID.
 *
 * @return object
 */
add_filter('fl_builder_register_settings_form', function ($form, $id) {
    if ('row' !== $id) {
        return $form;
    }

    // General BB row preset.
    $style_field = [
        'row_class' => [
            'type' => 'select',
            'label' => 'Row Style Preset',
            'default' => 'row-preset-default',
            'options' => fs_get_row_preset_class_names(),
        ],
    ];

    $form['tabs']['style']['sections']['general']['fields'] = array_merge(
        $style_field,
        $form['tabs']['style']['sections']['general']['fields'],
    );

    // Background preset field for rows.
    // phpcs:ignore Generic.Files.LineLength.TooLong
    $form['tabs']['style']['sections']['background']['fields']['bg_type']['options']['preset'] = 'Preset';
    $form['tabs']['style']['sections']['background']['fields']['bg_type']['toggle']['preset'] = [
        'fields' => [
            'bg_preset_class',
        ],
    ];
    $form['tabs']['style']['sections']['background']['fields']['bg_preset_class'] = [
        'type' => 'select',
        'label' => 'Preset',
        'default' => 'bg-default',
        'options' => fs_get_background_class_names(),
    ];

    return $form;
}, 10, 2);

/**
 * Add classes to BB row.
 *
 * @param array  $attrs Row attributes
 * @param object $row   Row object
 *
 * @return mixed
 */
add_filter('fl_builder_row_attributes', function ($attrs, $row) {
    if ('row' !== $row->type) {
        return $attrs;
    }

    /* Add class for row preset */
    $row_class = $row->settings->row_class ?? null;
    if (! in_array($row_class, [null, 'default'], true)) {
        $attrs['class'][] = $row_class;
    }

    /* Add class for background preset */
    $bg_class = $row->settings->bg_preset_class ?? null;
    if (! in_array($bg_class, [null, 'default'], true)) {
        $attrs['class'][] = $bg_class;
    }

    return $attrs;
}, 10, 2);

/**
 * Set our custom button preset settings to Button module settings.
 *
 * @param object $settings
 * @param array  $presets
 *
 * @return object
 */
function fs_set_button_preset($settings, $presets)
{
    if ('default' === ($settings->button_preset ?? 'default')) {
        return $settings;
    }

    /**
     * If the selected preset does not exist in our array
     * we set the `$custom_settings` variable to null
     * because we know that it is not valid for use.
     */
    $custom_settings = $presets[$settings->button_preset]['settings'] ?? null;
    // If it is null, exit the function early since there is nothing to do.
    if (null === $custom_settings) {
        return $settings;
    }

    // Loop our custom settings and apply to the main `$settings` object.
    foreach ($custom_settings as $key => $setting) {
        $settings->{$key} = $setting;
    }

    // Like before, look for a value and exit early if it fails.
    $custom_class = $presets[$settings->button_preset]['class'] ?? null;
    if (null === $custom_class) {
        return $settings;
    }

    // If the object doesn't have a `class` property, exit early.
    if (! property_exists($settings, 'class')) {
        return $settings;
    }

    // Finally, add the new classname.
    if (false === strpos($settings->class, $custom_class)) {
        $settings->class .= " {$custom_class}";
    }

    return $settings;
}

/**
 * Set our custom button preset settings to CTA module's button settings.
 *
 * @param object $settings
 * @param array  $presets
 *
 * @return object
 */
function fs_set_cta_preset($settings, $presets)
{
    if ('default' === $settings->button_preset) {
        return $settings;
    }

    $custom_settings = $presets[$settings->button_preset]['settings'];
    $custom_class = isset($presets[$settings->button_preset]['class'])
        ? $presets[$settings->button_preset]['class'] : '';

    foreach ($custom_settings as $key => $setting) {
        $settings->{"btn_{$key}"} = $setting;
    }

    if ('' !== $custom_class && property_exists($settings, 'class')) {
        $settings->class .= $custom_class;
    }

    return $settings;
}

/**
 * Overriding Beaver Builder's Button Module's default settings
 *
 * @param object $settings The module settings object.
 *
 * @return object
 */
add_filter('fl_builder_node_settings', function ($settings) {
    if (false === property_exists($settings, 'type')) {
        return $settings;
    }

    if (! in_array($settings->type, ['button', 'button-group', 'cta'], true)) {
        return $settings;
    }

    $presets = fs_get_bb_button_presets();
    if (empty($presets)) {
        return $settings;
    }

    switch ($settings->type) {
        case 'button':
            $settings = fs_set_button_preset($settings, $presets);
            break;
        case 'button-group':
            $settings->items = array_map(function ($item) use ($presets) {
                return fs_set_button_preset($item, $presets);
            }, $settings->items);
            break;
        case 'cta':
            $settings = fs_set_cta_preset($settings, $presets);
            break;
    }

    return $settings;
});
