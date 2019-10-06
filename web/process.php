<?php

saveIntoFile($_POST, CONFIG_BOT_FILENAME);
header("Location: ./" . $_POST['lang']);

function processGroups($input) {
    $fieldNames = ['currency', 'pay_min'];
    $init = $fieldNames[0];

    $res = [];

    foreach ($input[$init] as $key => $_) {
        foreach ($fieldNames as $name) {
            $res[$key][$name] = $input[$name][$key];
        }
    }

    return $res;
}

function convertToNestedGroupsByField($name, $groups) {
    $res = [];

    foreach ($groups as $group) {
        $groupName = strtoupper($group[$name]);
        unset($group[$name]);
        $res[$groupName] = $group;
    }

    return $res;
}

function flatByField($field, $groups) {
    $res = [];
    foreach ($groups as $name => $group) {
        $res[] = array_merge([
            $field => $name,
        ], $group);
    }

    return $res;
}

function validateGroups($groups) {
    $nested = convertToNestedGroupsByField(BASE_FIELD, $groups);

    return flatByField(BASE_FIELD, removeEmpty($nested));
}

function removeEmpty($groups) {
    foreach ($groups as $name => $_) {
        if (empty($name)) {
            unset($groups[$name]);
        }
    }

    return $groups;
}

function saveIntoFile($input, $filename) {
    $groups = validateGroups(processGroups($input));

    $result = [
        'groups' => $groups ?? [],
        'interval' => $input['interval'],
        'address' => $input['address'],
        'lang' => $input['lang'],
    ];

    $config = readConfigFromFile($filename);
    $result['description'] = $config['description'] ?: [];
    $result['description'][$input['lang']] = $input['description'];


    file_put_contents($filename, serialize($result));
    file_put_contents($filename . '.json', json_encode($result, 1));
}
