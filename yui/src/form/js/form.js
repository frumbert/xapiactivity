/**
 * JavaScript for form editing course completed condition.
 *
 * @module moodle-availability_xapiactivity-form
 */

M.availability_xapiactivity = M.availability_xapiactivity || {};

// Class M.availability_xapiactivity.form @extends M.core_availability.plugin.
M.availability_xapiactivity.form = Y.Object(M.core_availability.plugin);

// Options available for selection.
M.availability_xapiactivity.form.completed = null;

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {object} value passed from frontend.php, if any
 */
M.availability_xapiactivity.form.initInner = function(json) {
};

// code for drawing the actual form (appears after selecting the xAPI button in the restriction picker)
M.availability_xapiactivity.form.getNode = function(json) {

    // Create HTML structure.
    var tit = M.util.get_string('title', 'availability_xapiactivity');

    var html = '<label class="availability-group form-group"><span class="p-r-1">' + tit + '</label>';
    html += '<br>';
    html += '<label class="availability-group form-group"><span class="p-r-1">' + M.str.availability_xapiactivity.js_verb + '</span>';
    html += '<span class="availability-xapiactivity"><input type="text" size="40" name="verb" placeholder="http://adlnet.gov/expapi/verbs/completed"></span>';
    html += '&nbsp;<a href="https://registry.tincanapi.com/#home/verbs" target="_blank" title="Verb List at TinCan registry"><i class="fa fa-external-link"></i></a>';
    html += '</label><br>';
    html += '<label class="availability-group form-group"><span class="p-r-1">' + M.str.availability_xapiactivity.js_object + '</span>';
    html += '<span class="availability-xapiactivity"><input type="text" size="40" name="activity" placeholder="http://your-domain-name/activity/id"></span></label>';
    html += '<br>';
    html += '<label class="availability-group form-group"><span class="p-r-1">' + M.str.availability_xapiactivity.js_label + '</span>';
    html += '<span class="availability-xapiactivity"><input type="text" size="30" name="label" placeholder="User-facing label"></span></label>';
    var node = Y.Node.create('<span class="form-inline">' + html + '</span>');


    // Set initial values (leave default 'choose' if creating afresh).
    if (json.creating === undefined) {
        if (json.verb !== undefined && node.one('input[name=verb]')) {
            node.one('input[name=verb]').set('value', '' + json.verb);
        }
        if (json.activity !== undefined && node.one('input[name=activity]')) {
            node.one('input[name=activity]').set('value', '' + json.activity);
        }
        if (json.label !== undefined && node.one('input[name=label]')) {
            node.one('input[name=label]').set('value', '' + json.label);
        }
    }

    // Add event handlers (first time only).
    // On a form value change, save the value in the box back to the underlying form
    if (!M.availability_xapiactivity.form.addedEvents) {
        M.availability_xapiactivity.form.addedEvents = true;

        var root = Y.one('.availability-field');

        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_xapiactivity input[name=verb]');

        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_xapiactivity input[name=activity]');

        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_xapiactivity input[name=label]');

    }

    return node;
};

// persist the form values back to the underlying JSON object for this restriction
M.availability_xapiactivity.form.fillValue = function(value, node) {
    value.verb = node.one('input[name=verb]').get('value').trim();
    value.activity = node.one('input[name=activity]').get('value').trim();
    value.label = node.one('input[name=label]').get('value').trim();
};

// Notify the user if they haven't entered a value for any box
M.availability_xapiactivity.form.fillErrors = function(errors, node) {
    var verb = node.one('input[name=verb]').get('value').trim();
    var activity = node.one('input[name=activity]').get('value').trim();
    var label = node.one('input[name=label]').get('value').trim();
    if (activity === '' || verb === '' || label === '') {
        errors.push('availability_xapiactivity:missing');
    }
};
