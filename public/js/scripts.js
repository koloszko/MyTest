/* Functions implemented as jQuery plugins*/
(function($) {
    /* manages fields of dynamic collections */
    $.fn.dynamicCollection = function(options) {
        return this.each(function() {
            var $this = $(this), settings = {
                /* function invoked after adding new "row" into collection */
                postAddFunction : null
            };

            if (options)
                $.extend(settings, options);

            /* adding new "row" into collection after pressing "Add new" button */
            $this.find("input.addButton").show().click(function(event) {
                var elemRowClone = $this.find(".colElem:last").clone(),
                newIndex = $this.find(".colElem").size()+1;

                elemRowClone.find(":input:enabled").each(function() {
                    if (!$(this).attr("readonly")) {
                        if (this.name)
                            this.name = this.name.replace(/\d+/, newIndex);

                        if (this.id)
                            this.id = this.id.replace(/\d+/, newIndex);

                        if (this.options) {
                            this.options[0].selected = true;
                        } else {
                            if (this.value)
                                this.value = '';
                        }
                    }
                });

                elemRowClone.find("label").each(function() {
                    if (this.htmlFor)
                        this.htmlFor = this.htmlFor.replace(/\d+/, newIndex);
                });

                /* copied row can have validation errors - we are dealing with that */
                elemRowClone.find(".fieldError", ".validationWarning").hide();
                elemRowClone.find(".error").addClass("desc").show();

                $this.append(elemRowClone);

                if (settings.postAddFunction)
                    settings.postAddFunction(elemRowClone);

                elemRowClone.show("blind", "slow");
            });

            /* deleting "row" from collection after pressing delete button */
            $this.find(".delButton").show().live("click", function(event) {
                var row = $(event.target.parentNode), 
                visibleRows = row.parent().find(".colElem:visible").size();

                if (visibleRows > 1)
                    row.hide("blind", "slow");

                row.find(":input:enabled").each(function() {
                    if (!$(this).attr("readonly")) {
                        if (this.options) {
                            this.options[0].selected = true;
                        } else {
                            if (this.value)
                                /* By default during removing element its value is cleared, element marked with
				* revertWhileRemoved class is not cleared, but minus sign is added to it. It adds
				* another posibility to send info to business layer about removing an object
				*/
                                if ($(this).hasClass("revertWhileRemoved"))
                                    this.value = '-' + this.value;
                                else
                                    this.value = '';
                        }
                    }
                });
            });

            /* enable disabled inputs */
            $this.find("input.text:not(.alwaysDisabled)").removeAttr("readOnly").removeAttr("disabled").removeClass('disabledField');
        });
    };

    /* limits maximum amount of characters typed in textarea */
    $.fn.limitMaxlength = function(maxlength) {
        // Event handler to limit the textarea
        var onEdit = function() {
            var textarea = $(this);

            if (textarea.val().length > maxlength) {
                textarea.val(textarea.val().substr(0, maxlength));
            }
        }

        this.each(onEdit);

        return this.live('keyup',onEdit)
        .live('keydown',onEdit)
        .live('focus',onEdit)
        .live('input paste', onEdit);
    };

    /* plugin adds text to label, which is placed just before DOM element */
    $.fn.extendLabel = function(txt) {
        return this.each(function() {
            var lb = $(this).prev("label")
            lb.text(lb.text() + " " + txt);
        });
    };
	
    $.fn.preventClicking = function() {
        $(this).click(function() {
            $("a,input,button,fieldset.dynamicCollection").unbind('click').click(function(event) {
                event.preventDefault();
            });
            $("a.remove").die();
        });
    };
})(jQuery);


function initializeTownsAutocomplete(autoCompleteSelector) {
    $(autoCompleteSelector).autocomplete( {
        source : function(request, response) {
            $.ajax( {
                url : BASE_URL + "/person/towns",
                data : {
                    term : request.term
                },
                success : function(data) {
                    response($.map(data, function(item) {
                        return {
                            label : item.name,
                            value : item.name
                        };
                    }));
                }
            });
        },
        minLength : 2  
    });
}