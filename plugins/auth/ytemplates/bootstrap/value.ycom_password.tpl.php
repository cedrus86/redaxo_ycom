<?php

$type = isset($type) ? $type : 'text';
$class = $type == 'text' ? '' : 'form-' . $type . ' ';
if (!isset($value)) {
    $value = $this->getValue();
}

$notice = [];
if ($this->getElement('notice') != '') {
    $notice[] = rex_i18n::translate($this->getElement('notice'), false);
}
if (isset($this->params['warning_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notice[] = '<span class="text-warning">' . rex_i18n::translate($this->params['warning_messages'][$this->getId()]) . '</span>'; //    var_dump();
}
if (count($notice) > 0) {
    $notice = '<p class="help-block">' . implode('<br />', $notice) . '</p>';
} else {
    $notice = '';
}

$class_group = trim('form-group yform-element ' . $this->getWarningClass());

$class_label[] = 'control-label';

$attributes = [
    'class' => 'form-control',
    'name' => $this->getFieldName(),
    'type' => $type,
    'id' => $this->getFieldId(),
    'value' => $value,
    'autocomplete' => 'new-password',
];

$attributes = $this->getAttributeElements($attributes, ['placeholder', 'autocomplete', 'pattern', 'required', 'disabled', 'readonly']);

$span = '';
if ($script) {
    $funcName = uniqid('rex_ycom_password_create'.$this->getId());
    $span = '<span class="input-group-btn">
    <button type="button" class="btn btn-default getNewPass" onclick="'.$funcName.'eye('.$this->getId().')"><span class="fa fa-eye"></span></button>
    <button type="button" class="btn btn-default getNewPass" onclick="'.$funcName.'refresh('.$this->getId().')"><span class="fa fa-refresh"></span></button>
    </span>'; ?><script type="text/javascript">

        // Credit to @Blender https://stackoverflow.com/users/464744/blender
        String.prototype.pick = function(min, max) {
            var n, chars = '';

            if (typeof max === 'undefined') {
                n = min;
            } else {
                n = min + Math.floor(Math.random() * (max - min + 1));
            }

            for (var i = 0; i < n; i++) {
                chars += this.charAt(Math.floor(Math.random() * this.length));
            }

            return chars;
        };

        // Credit to @Christoph: http://stackoverflow.com/a/962890/464744
        String.prototype.shuffle = function() {
            var array = this.split('');
            var tmp, current, top = array.length;

            if (top) while (--top) {
                current = Math.floor(Math.random() * (top + 1));
                tmp = array[current];
                array[current] = array[top];
                array[top] = tmp;
            }

            return array.join('');
        };

        function <?= $funcName.'refresh' ?>(input) {

            var rules = {
                    letter:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
                    uppercase:"ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                    lowercase: "abcdefghijklmnopqrstuvwxyz",
                    digit: "0123456789",
                    symbol: "!@#$%^&*()_+{}:\"<>?\|[];',./`~",
                    all: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}:\"<>?|[];',./`~",
                };
            rules.letter = rules.uppercase + rules.lowercase;
            rules.all = rules.uppercase + rules.lowercase + rules.digit + rules.symbol;

            var ruleset = '';
            var myRules = <?php echo json_encode($rules); ?>;
            var myPassword = '';

            if (typeof myRules.uppercase === "object") {
                min = myRules.uppercase.min;
                if (typeof myRules.uppercase.min === "undefined") {
                    min = 1;
                }
                max = myRules.uppercase.max;
                if (typeof myRules.uppercase.max === "undefined") {
                    max = min;
                }
                ruleset += rules.uppercase;
                myPassword += rules.uppercase.pick(min,max);
            }
            if (typeof myRules.lowercase === "object") {
                min = myRules.lowercase.min;
                if (typeof myRules.lowercase.min === "undefined") {
                    min = 1;
                }
                max = myRules.lowercase.max;
                if (typeof myRules.lowercase.max === "undefined") {
                    max = min;
                }
                ruleset += rules.lowercase;
                myPassword += rules.lowercase.pick(min,max);
            }
            if (typeof myRules.letter === "object") {
                min = myRules.letter.min;
                if (typeof myRules.letter.min === "undefined") {
                    min = 1;
                }
                max = myRules.letter.max;
                if (typeof myRules.letter.max === "undefined") {
                    max = min;
                }
                if (min > myPassword.length) {
                    min = min - myPassword.length;
                } else {
                    min = 0;
                }

                if (max > myPassword.length) {
                    max = max - myPassword.length;
                } else {
                    min = 0;
                }
                myPassword += ruleset.pick(min,max);

            }
            if (typeof myRules.digit === "object") {
                min = myRules.digit.min;
                if (typeof myRules.digit.min === "undefined") {
                    min = 1;
                }
                max = myRules.digit.max;
                if (typeof myRules.digit.max === "undefined") {
                    max = min;
                }
                ruleset += rules.digit;
                myPassword += rules.digit.pick(min,max);
            }
            if (typeof myRules.symbol === "object") {
                min = myRules.symbol.min;
                if (typeof myRules.symbol.min === "undefined") {
                    min = 1;
                }
                max = myRules.symbol.max;
                if (typeof myRules.symbol.max === "undefined") {
                    max = min;
                }
                ruleset += rules.symbol;
                myPassword += rules.symbol.pick(min,max);
            }

            if (typeof myRules.length === "object") {
                min = myRules.length.min;
                if (typeof myRules.length.min === "undefined") {
                    min = 1;
                }
                max = myRules.length.max;
                if (typeof myRules.length.max === "undefined") {
                    max = min;
                }
                if (min > myPassword.length) {
                    min = min - myPassword.length;
                } else {
                    min = 0;
                }

                if (max > myPassword.length) {
                    max = max - myPassword.length;
                } else {
                    min = 0;
                }

                myPassword += ruleset.pick(min,max);

            }

            var item = document.getElementsByName('<?php echo $this->getFieldName(); ?>').item(0);
            var name = item.getAttribute('name');
            var type = item.getAttribute('value');

            item.value = myPassword;

        }
        function <?= $funcName.'eye' ?>(input) {
            var item = document.getElementsByName('<?php echo $this->getFieldName(); ?>').item(0);
            var name = item.getAttribute('name');
            var type = item.getAttribute('type');
            if ('password' == type) {
                item.setAttribute('type', 'text');
            } else {
                item.setAttribute('type', 'password');
            }
        }
    </script><?php
}

echo '
<div class="'.$class_group.'" id="'.$this->getHTMLId().'">
<label class="'.implode(' ', $class_label).'" for="'.$this->getFieldId().'">'.$this->getLabel().'</label>
<div class="input-group mif">
    <input '.implode(' ', $attributes).' />' .
    $notice .
    $span . '
</div>
</div>';

?>