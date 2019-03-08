$(document).ready(function ($) {

    $('.input-positive').mask("0#");

    $(".input-money").priceFormat({
        limit: 12,
        allowNegative: false
    });

    // $('.select2').select2({
    //     width: '95%',
    //     allow_single_deselect: true,
    //     no_results_text: "Nenhum resultado encontrado!",
    //     placeholder: "Seleciona uma opção."
    // });

    $('.input-data').mask('99/99/9999');

    $(".input-money-3-decimais").priceFormat({
        limit: 12,
        centsLimit: 3,
        allowNegative: false
    });

    $(".input-money-can-be-negative").priceFormat({
        allowNegative: true
    });

    $("#cnpj").mask("00.000.000/0000-00", {clearIfNotMatch: true});

    $("#cnpj-pessoa").mask("00.000.000/0000-00", {clearIfNotMatch: true});

    $("#cpf").mask('000.000.000-00', {clearIfNotMatch: true});

    $(".input-cpf").mask('000.000.000-00', {clearIfNotMatch: true});

    $("#cpf-pessoa").mask('000.000.000-00', {clearIfNotMatch: true});

    $(".input-cep").mask("00.000-000", {clearIfNotMatch: true});

    $(".input-cnpj").mask("00.000.000/0000-00", {clearIfNotMatch: true});

    $(".input-serie-nf, .input-ecf").mask("000");

    $(".input-nf").mask("000000000");

    $(".input-coo").mask("000000");

    $(".input-fone").mask(MascaraFoneSP, spOptions);

    $(".input-placa").mask("SSS-0000");

    $(".input-uf").mask("SS");

    $(".input-ie").mask("00000000000000");

    $(".input-dia, .input-mes").mask("00").blur(function () {
        if ($(this).val() < 10) {
            $(this).val('0' + parseInt($(this).val()));
        }
    });

    $('.input-date-time-picker').mask("00/00/0000 00:00:00", {clearIfNotMatch: true});
    $('.input-date').mask("00/00/0000", {clearIfNotMatch: true});
    $(".input-ano").mask("0000");

    $(".input-chave-nfe-cte").mask("0000   0000   0000   0000   0000   0000   0000   0000   0000   0000   0000");

    $('.input-cpf-cnpj').mask(MascaraCpfCnpj, cpfCnpjOptions);
});

var MascaraFoneSP = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
}, spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(MascaraFoneSP.apply({}, arguments), options);
    },
    clearIfNotMatch: true
};

var MascaraCpfCnpj = function (val) {
    var masks = ['000.000.000-000', '00.000.000/0000-00'];

    if (val.length <= 14) {
        return masks[0];
    }
    if (val.length > 14) {
        return masks[1];
    }
};

var cpfCnpjOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(MascaraCpfCnpj.apply({}, arguments), options);
    },
    reverse: false
};

/**
 *  Jquey mask plugin igor escobar ;
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery || Zepto);
    }
}
(function ($) {
    var Mask = function (el, mask, options) {
        el = $(el);

        var jMask = this, oldValue = el.val(), regexMask;

        mask = typeof mask === 'function' ? mask(el.val(), undefined, el, options) : mask;

        var p = {
            invalid: [],
            getCaret: function () {
                try {
                    var sel,
                        pos = 0,
                        ctrl = el.get(0),
                        dSel = document.selection,
                        cSelStart = ctrl.selectionStart;

                    // IE Support
                    if (dSel && navigator.appVersion.indexOf('MSIE 10') === -1) {
                        sel = dSel.createRange();
                        sel.moveStart('character', el.is('input') ? -el.val().length : -el.text().length);
                        pos = sel.text.length;
                    }
                    // Firefox support
                    else if (cSelStart || cSelStart === '0') {
                        pos = cSelStart;
                    }

                    return pos;
                } catch (e) {
                }
            },
            setCaret: function (pos) {
                try {
                    if (el.is(':focus')) {
                        var range, ctrl = el.get(0);

                        if (ctrl.setSelectionRange) {
                            ctrl.setSelectionRange(pos, pos);
                        } else if (ctrl.createTextRange) {
                            range = ctrl.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', pos);
                            range.moveStart('character', pos);
                            range.select();
                        }
                    }
                } catch (e) {
                }
            },
            events: function () {
                el
                    .on('input.mask keyup.mask', p.behaviour)
                    .on('paste.mask drop.mask', function () {
                        setTimeout(function () {
                            el.keydown().keyup();
                        }, 100);
                    })
                    .on('change.mask', function () {
                        el.data('changed', true);
                    })
                    .on('blur.mask', function () {
                        if (oldValue !== el.val() && !el.data('changed')) {
                            el.triggerHandler('change');
                        }
                        el.data('changed', false);
                    })
                    // it's very important that this callback remains in this position
                    // otherwhise oldValue it's going to work buggy
                    .on('blur.mask', function () {
                        oldValue = el.val();
                    })
                    // select all text on focus
                    .on('focus.mask', function (e) {
                        if (options.selectOnFocus === true) {
                            $(e.target).select();
                        }
                    })
                    // clear the value if it not complete the mask
                    .on('focusout.mask', function () {
                        if (options.clearIfNotMatch && !regexMask.test(p.val())) {
                            p.val('');
                        }
                    });
            },
            getRegexMask: function () {
                var maskChunks = [], translation, pattern, optional, recursive, oRecursive, r;

                for (var i = 0; i < mask.length; i++) {
                    translation = jMask.translation[mask.charAt(i)];

                    if (translation) {

                        pattern = translation.pattern.toString().replace(/.{1}$|^.{1}/g, '');
                        optional = translation.optional;
                        recursive = translation.recursive;

                        if (recursive) {
                            maskChunks.push(mask.charAt(i));
                            oRecursive = {digit: mask.charAt(i), pattern: pattern};
                        } else {
                            maskChunks.push(!optional && !recursive ? pattern : (pattern + '?'));
                        }

                    } else {
                        maskChunks.push(mask.charAt(i).replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'));
                    }
                }

                r = maskChunks.join('');

                if (oRecursive) {
                    r = r.replace(new RegExp('(' + oRecursive.digit + '(.*' + oRecursive.digit + ')?)'), '($1)?')
                        .replace(new RegExp(oRecursive.digit, 'g'), oRecursive.pattern);
                }

                return new RegExp(r);
            },
            destroyEvents: function () {
                el.off(['input', 'keydown', 'keyup', 'paste', 'drop', 'blur', 'focusout', ''].join('.mask '));
            },
            val: function (v) {
                var isInput = el.is('input'),
                    method = isInput ? 'val' : 'text',
                    r;

                if (arguments.length > 0) {
                    if (el[method]() !== v) {
                        el[method](v);
                    }
                    r = el;
                } else {
                    r = el[method]();
                }

                return r;
            },
            getMCharsBeforeCount: function (index, onCleanVal) {
                for (var count = 0, i = 0, maskL = mask.length; i < maskL && i < index; i++) {
                    if (!jMask.translation[mask.charAt(i)]) {
                        index = onCleanVal ? index + 1 : index;
                        count++;
                    }
                }
                return count;
            },
            caretPos: function (originalCaretPos, oldLength, newLength, maskDif) {
                var translation = jMask.translation[mask.charAt(Math.min(originalCaretPos - 1, mask.length - 1))];

                return !translation ? p.caretPos(originalCaretPos + 1, oldLength, newLength, maskDif)
                    : Math.min(originalCaretPos + newLength - oldLength - maskDif, newLength);
            },
            behaviour: function (e) {
                e = e || window.event;
                p.invalid = [];
                var keyCode = e.keyCode || e.which;
                if ($.inArray(keyCode, jMask.byPassKeys) === -1) {

                    var caretPos = p.getCaret(),
                        currVal = p.val(),
                        currValL = currVal.length,
                        changeCaret = caretPos < currValL,
                        newVal = p.getMasked(),
                        newValL = newVal.length,
                        maskDif = p.getMCharsBeforeCount(newValL - 1) - p.getMCharsBeforeCount(currValL - 1);

                    p.val(newVal);

                    // change caret but avoid CTRL+A
                    if (changeCaret && !(keyCode === 65 && e.ctrlKey)) {
                        // Avoid adjusting caret on backspace or delete
                        if (!(keyCode === 8 || keyCode === 46)) {
                            caretPos = p.caretPos(caretPos, currValL, newValL, maskDif);
                        }
                        p.setCaret(caretPos);
                    }

                    return p.callbacks(e);
                }
            },
            getMasked: function (skipMaskChars) {
                var buf = [],
                    value = p.val(),
                    m = 0, maskLen = mask.length,
                    v = 0, valLen = value.length,
                    offset = 1, addMethod = 'push',
                    resetPos = -1,
                    lastMaskChar,
                    check;

                if (options.reverse) {
                    addMethod = 'unshift';
                    offset = -1;
                    lastMaskChar = 0;
                    m = maskLen - 1;
                    v = valLen - 1;
                    check = function () {
                        return m > -1 && v > -1;
                    };
                } else {
                    lastMaskChar = maskLen - 1;
                    check = function () {
                        return m < maskLen && v < valLen;
                    };
                }

                while (check()) {
                    var maskDigit = mask.charAt(m),
                        valDigit = value.charAt(v),
                        translation = jMask.translation[maskDigit];

                    if (translation) {
                        if (valDigit.match(translation.pattern)) {
                            buf[addMethod](valDigit);
                            if (translation.recursive) {
                                if (resetPos === -1) {
                                    resetPos = m;
                                } else if (m === lastMaskChar) {
                                    m = resetPos - offset;
                                }

                                if (lastMaskChar === resetPos) {
                                    m -= offset;
                                }
                            }
                            m += offset;
                        } else if (translation.optional) {
                            m += offset;
                            v -= offset;
                        } else if (translation.fallback) {
                            buf[addMethod](translation.fallback);
                            m += offset;
                            v -= offset;
                        } else {
                            p.invalid.push({p: v, v: valDigit, e: translation.pattern});
                        }
                        v += offset;
                    } else {
                        if (!skipMaskChars) {
                            buf[addMethod](maskDigit);
                        }

                        if (valDigit === maskDigit) {
                            v += offset;
                        }

                        m += offset;
                    }
                }

                var lastMaskCharDigit = mask.charAt(lastMaskChar);
                if (maskLen === valLen + 1 && !jMask.translation[lastMaskCharDigit]) {
                    buf.push(lastMaskCharDigit);
                }

                return buf.join('');
            },
            callbacks: function (e) {
                var val = p.val(),
                    changed = val !== oldValue,
                    defaultArgs = [val, e, el, options],
                    callback = function (name, criteria, args) {
                        if (typeof options[name] === 'function' && criteria) {
                            options[name].apply(this, args);
                        }
                    };

                callback('onChange', changed === true, defaultArgs);
                callback('onKeyPress', changed === true, defaultArgs);
                callback('onComplete', val.length === mask.length, defaultArgs);
                callback('onInvalid', p.invalid.length > 0, [val, e, el, p.invalid, options]);
            }
        };


        // public methods
        jMask.mask = mask;
        jMask.options = options;
        jMask.remove = function () {
            var caret = p.getCaret();
            p.destroyEvents();
            p.val(jMask.getCleanVal());
            p.setCaret(caret - p.getMCharsBeforeCount(caret));
            return el;
        };

        // get value without mask
        jMask.getCleanVal = function () {
            return p.getMasked(true);
        };

        jMask.init = function (onlyMask) {
            onlyMask = onlyMask || false;
            options = options || {};

            jMask.byPassKeys = $.jMaskGlobals.byPassKeys;
            jMask.translation = $.jMaskGlobals.translation;

            jMask.translation = $.extend({}, jMask.translation, options.translation);
            jMask = $.extend(true, {}, jMask, options);

            regexMask = p.getRegexMask();

            if (onlyMask === false) {

                if (options.placeholder) {
                    el.attr('placeholder', options.placeholder);
                }

                // this is necessary, otherwise if the user submit the form
                // and then press the "back" button, the autocomplete will erase
                // the data. Works fine on IE9+, FF, Opera, Safari.
                if ($('input').length && 'oninput' in $('input')[0] === false && el.attr('autocomplete') === 'on') {
                    el.attr('autocomplete', 'off');
                }

                p.destroyEvents();
                p.events();

                var caret = p.getCaret();
                p.val(p.getMasked());
                p.setCaret(caret + p.getMCharsBeforeCount(caret, true));

            } else {
                p.events();
                p.val(p.getMasked());
            }
        };

        jMask.init(!el.is('input'));
    };
    $.maskWatchers = {};
    var HTMLAttributes = function () {
            var input = $(this),
                options = {},
                prefix = 'data-mask-',
                mask = input.attr('data-mask');

            if (input.attr(prefix + 'reverse')) {
                options.reverse = true;
            }

            if (input.attr(prefix + 'clearifnotmatch')) {
                options.clearIfNotMatch = true;
            }

            if (input.attr(prefix + 'selectonfocus') === 'true') {
                options.selectOnFocus = true;
            }

            if (notSameMaskObject(input, mask, options)) {
                return input.data('mask', new Mask(this, mask, options));
            }
        },
        notSameMaskObject = function (field, mask, options) {
            options = options || {};
            var maskObject = $(field).data('mask'),
                stringify = JSON.stringify,
                value = $(field).val() || $(field).text();
            try {
                if (typeof mask === 'function') {
                    mask = mask(value);
                }
                return typeof maskObject !== 'object' || stringify(maskObject.options) !== stringify(options) || maskObject.mask !== mask;
            } catch (e) {
            }
        };
    $.fn.mask = function (mask, options) {
        options = options || {};
        var selector = this.selector,
            globals = $.jMaskGlobals,
            interval = $.jMaskGlobals.watchInterval,
            maskFunction = function () {
                if (notSameMaskObject(this, mask, options)) {
                    return $(this).data('mask', new Mask(this, mask, options));
                }
            };

        $(this).each(maskFunction);

        if (selector && selector !== '' && globals.watchInputs) {
            clearInterval($.maskWatchers[selector]);
            $.maskWatchers[selector] = setInterval(function () {
                $(document).find(selector).each(maskFunction);
            }, interval);
        }
        return this;
    };
    $.fn.unmask = function () {
        clearInterval($.maskWatchers[this.selector]);
        delete $.maskWatchers[this.selector];
        return this.each(function () {
            var Mask = $(this).data('mask');
            if (dataMask) {
                dataMask.remove().removeData('mask');
            }
        });
    };
    $.fn.cleanVal = function () {
        return this.data('mask').getCleanVal();
    };
    $.applyDataMask = function (selector) {
        selector = selector || $.jMaskGlobals.maskElements;
        var $selector = (selector instanceof $) ? selector : $(selector);
        $selector.filter($.jMaskGlobals.dataMaskAttr).each(HTMLAttributes);
    };
    var globals = {
        maskElements: 'input,td,span,div',
        dataMaskAttr: '*[data-mask]',
        dataMask: true,
        watchInterval: 300,
        watchInputs: true,
        watchDataMask: false,
        byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91],
        translation: {
            '0': {pattern: /\d/},
            '9': {pattern: /\d/, optional: true},
            '#': {pattern: /\d/, recursive: true},
            'A': {pattern: /[a-zA-Z0-9]/},
            'S': {pattern: /[a-zA-Z]/}
        }
    };
    $.jMaskGlobals = $.jMaskGlobals || {};
    globals = $.jMaskGlobals = $.extend(true, {}, globals, $.jMaskGlobals);
    // looking for inputs with data-mask attribute
    if (globals.dataMask) {
        $.applyDataMask();
    }

    setInterval(function () {
        if ($.jMaskGlobals.watchDataMask) {
            $.applyDataMask();
        }
    }, globals.watchInterval);
}));


/*
 * Price Format jQuery Plugin
 * Created By Eduardo Cuducos
 * Currently maintained by Flavio Silveira flavio [at] gmail [dot] com
 */
(function ($) {

    /****************
     * Main Function *
     *****************/
    $.fn.priceFormat = function (options) {

        var options = $.extend(true, {}, $.fn.priceFormat.defaults, options);

        // detect if ctrl or metaKey(Mac) is pressed
        window.ctrl_down = false
        metaKey = false

        $(window).bind('keyup keydown', function (e) {
            window.ctrl_down = e.ctrlKey;
            return true;
        });

        $(this).bind('keyup keydown', function (e) {
            metaKey = e.metaKey;
            return true;
        });

        return this.each(function () {
            // pre defined options
            var obj = $(this);
            var value = '';
            var is_number = /[0-9]/;

            // Check if is an input
            if (obj.is('input'))
                value = obj.val();
            else
                value = obj.html();

            // load the pluggings settings
            var prefix = options.prefix;
            var suffix = options.suffix;
            var centsSeparator = options.centsSeparator;
            var thousandsSeparator = options.thousandsSeparator;
            var limit = options.limit;
            var centsLimit = options.centsLimit;
            var clearPrefix = options.clearPrefix;
            var clearSuffix = options.clearSuffix;
            var allowNegative = options.allowNegative;
            var insertPlusSign = options.insertPlusSign;
            var clearOnEmpty = options.clearOnEmpty;
            var leadingZero = options.leadingZero;

            // If insertPlusSign is on, it automatic turns on allowNegative, to work with Signs
            if (insertPlusSign) allowNegative = true;

            function set(nvalue) {
                if (obj.is('input'))
                    obj.val(nvalue);
                else
                    obj.html(nvalue);

                obj.trigger('pricechange');
            }

            function get() {
                if (obj.is('input'))
                    value = obj.val();
                else
                    value = obj.html();

                return value;
            }

            // skip everything that isn't a number
            // and also skip the left zeroes
            function to_numbers(str) {
                var formatted = '';
                for (var i = 0; i < (str.length); i++) {
                    char_ = str.charAt(i);
                    if (formatted.length == 0 && char_ == 0) char_ = false;

                    if (char_ && char_.match(is_number)) {
                        if (limit) {
                            if (formatted.length < limit) formatted = formatted + char_;
                        } else {
                            formatted = formatted + char_;
                        }
                    }
                }

                return formatted;
            }

            // format to fill with zeros to complete cents chars
            function fill_with_zeroes(str) {
                while (str.length < (centsLimit + 1)) str = '0' + str;
                return str;
            }

            // format as price
            function price_format(str, ignore) {
                if (!ignore && (str === '' || str == price_format('0', true)) && clearOnEmpty)
                    return '';

                // formatting settings
                var formatted = fill_with_zeroes(to_numbers(str));
                var thousandsFormatted = '';
                var thousandsCount = 0;

                // Checking CentsLimit
                if (centsLimit == 0) {
                    centsSeparator = "";
                    centsVal = "";
                }

                // split integer from cents
                var centsVal = formatted.substr(formatted.length - centsLimit, centsLimit);
                var integerVal = formatted.substr(0, formatted.length - centsLimit);

                // apply cents pontuation
                // This stops from adding a leading Zero '0.00' -> '.00'
                if (leadingZero) {
                    formatted = integerVal + centsSeparator + centsVal;
                } else {
                    if (integerVal !== "0") {
                        formatted = integerVal + centsSeparator + centsVal;
                    }
                    else {
                        formatted = centsSeparator + centsVal;
                    }
                }

                // apply thousands pontuation
                if (thousandsSeparator || $.trim(thousandsSeparator) != "") {
                    for (var j = integerVal.length; j > 0; j--) {
                        char_ = integerVal.substr(j - 1, 1);
                        thousandsCount++;
                        if (thousandsCount % 3 == 0) char_ = thousandsSeparator + char_;
                        thousandsFormatted = char_ + thousandsFormatted;
                    }

                    //
                    if (thousandsFormatted.substr(0, 1) == thousandsSeparator) thousandsFormatted = thousandsFormatted.substring(1, thousandsFormatted.length);
                    formatted = (centsLimit == 0) ? thousandsFormatted : thousandsFormatted + centsSeparator + centsVal;
                }

                // if the string contains a dash, it is negative - add it to the begining (except for zero)
                if (allowNegative && (integerVal != 0 || centsVal != 0)) {
                    if (str.indexOf('-') != -1 && str.indexOf('+') < str.indexOf('-')) {
                        formatted = '-' + formatted;
                    } else {
                        if (!insertPlusSign)
                            formatted = '' + formatted;
                        else
                            formatted = '+' + formatted;
                    }
                }

                // apply the prefix
                if (prefix) formatted = prefix + formatted;

                // apply the suffix
                if (suffix) formatted = formatted + suffix;

                return formatted;
            }

            // filter what user type (only numbers and functional keys)
            function key_check(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                var typed = String.fromCharCode(code);
                var functional = false;
                var str = value;
                var newValue = price_format(str + typed);

                // allow key numbers, 0 to 9
                if ((code >= 48 && code <= 57) || (code >= 96 && code <= 105)) functional = true;
                if (code == 192) functional = true;

                // check Backspace, Tab, Enter, Delete, and left/right arrows
                if (code == 8) functional = true;
                if (code == 9) functional = true;
                if (code == 13) functional = true;
                if (code == 46) functional = true;
                if (code == 37) functional = true;
                if (code == 39) functional = true;
                // Minus Sign, Plus Sign
                if (allowNegative && (code == 189 || code == 109 || code == 173)) functional = true;
                if (insertPlusSign && (code == 187 || code == 107 || code == 61)) functional = true;
                //Allow Home, End, Shift, Caps Lock, Esc
                if (code >= 16 && code <= 20) functional = true;
                if (code == 27) functional = true;
                if (code >= 33 && code <= 40) functional = true;
                if (code >= 44 && code <= 46) functional = true;

                // allow Ctrl shortcuts (copy, paste etc.)
                if (window.ctrl_down || metaKey) {
                    if (code == 86) functional = true; // v: paste
                    if (code == 67) functional = true; // c: copy
                    if (code == 88) functional = true; // x: cut
                    if (code == 82) functional = true; // r: reload
                    if (code == 84) functional = true; // t: new tab
                    if (code == 76) functional = true; // l: URL bar
                    if (code == 87) functional = true; // w: close window/tab
                    if (code == 81) functional = true; // q: quit
                    if (code == 78) functional = true; // n: new window/tab
                    if (code == 65) functional = true; // a: select all
                }

                if (!functional) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (str != newValue) set(newValue);
                }

            }

            // Formatted price as a value
            function price_it() {
                var str = get();
                var price = price_format(str);
                if (str != price) set(price);
                var format = price_format('0', true);
                if (price == format && str != '0' && clearOnEmpty) set('');
            }

            // Add prefix on focus
            function add_prefix() {
                obj.val(prefix + get());
            }

            function add_suffix() {
                obj.val(get() + suffix);
            }

            // Clear prefix on blur if is set to true
            function clear_prefix() {
                if ($.trim(prefix) != '' && clearPrefix) {
                    var array = get().split(prefix);
                    set(array[1]);
                }
            }

            // Clear suffix on blur if is set to true
            function clear_suffix() {
                if ($.trim(suffix) != '' && clearSuffix) {
                    var array = get().split(suffix);
                    set(array[0]);
                }
            }

            // bind the actions
            obj.bind('keydown.price_format', key_check);
            obj.bind('keyup.price_format', price_it);
            obj.bind('focusout.price_format', price_it);

            // Clear Prefix and Add Prefix
            if (clearPrefix) {
                obj.bind('focusout.price_format', function () {
                    clear_prefix();
                });

                obj.bind('focusin.price_format', function () {
                    add_prefix();
                });
            }

            // Clear Suffix and Add Suffix
            if (clearSuffix) {
                obj.bind('focusout.price_format', function () {
                    clear_suffix();
                });

                obj.bind('focusin.price_format', function () {
                    add_suffix();
                });
            }

            // If value has content
            if (get().length > 0) {
                price_it();
                clear_prefix();
                clear_suffix();
            }

        });

    };

    /**********************
     * Remove price format *
     ***********************/
    $.fn.unpriceFormat = function () {
        return $(this).unbind(".price_format");
    };

    /******************
     * Unmask Function *
     *******************/
    $.fn.unmask = function () {

        var field;
        var result = "";

        if ($(this).is('input'))
            field = $(this).val() || [];
        else
            field = $(this).html();

        for (var f = 0; f < field.length; f++) {
            if (!isNaN(field[f]) || field[f] == "-") result += field[f];
        }

        return result;
    };

    /******************
     * Price to Float *
     ******************/
    $.fn.priceToFloat = function () {

        if ($(this).is('input'))
            field = $(this).val() || [];
        else
            field = $(this).html();

        return parseFloat(field.replace(/[^0-9\-\.]/g, ''));
    };

    /************
     * Defaults *
     ************/
    $.fn.priceFormat.defaults = {
        prefix: '',
        suffix: '',
        centsSeparator: ',',
        thousandsSeparator: '.',
        limit: false,
        centsLimit: 2,
        clearPrefix: false,
        clearSufix: false,
        allowNegative: false,
        insertPlusSign: false,
        clearOnEmpty: false,
        leadingZero: true
    };

})(jQuery);