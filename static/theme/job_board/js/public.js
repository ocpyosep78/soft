var DATE_FORMAT = 'd-m-Y';
var TIME_FORMAT = 'H:i';

var Site = {
    Host: web.host,
    IsValidEmail: function (Email) {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailPattern.test(Email);  
    },
    IsValidYear: function(Value) {
        var Result = true;
        
        Value = Value + '';
        Value = Value.replace(new RegExp('[^0-9]', 'gi'), '');
        
        if (Value.length != 4) {
            Result = false;
        }
        
        return Result;
    },
    IsValidPostalCode: function(Value) {
        var Result = true;
        
        Value = Value + '';
        Value = Value.replace(new RegExp('[^0-9]', 'gi'), '');
        
        if (Value.length != 5) {
            Result = false;
        }
        
        return Result;
    },
    GetTimeFromString: function(String) {
        String = $.trim(String);
        if (String == '') {
            return new Date();
        }
        
        var Data = new Date();
        var ArrayData = String.split('-');
        if (ArrayData[2] != null && ArrayData[2].length == 4) {
            Data = new Date(ArrayData[2] + '-' + ArrayData[1] + '-' + ArrayData[0]);
        }
        
        return Data;
    },
	SwapYearDay: function(String) {
		var Temp = Site.GetTimeFromString(String);
		var Result = Temp.getFullYear() + '-' + Temp.getMonth() + '-' + Temp.getDate();
		return Result;
	},
    Form: {
		InlineWarning: function(Input) {
			Input.parent('td').append('<div class="CntWarning">' + Input.attr('alt') + '</div>');
		},
        Start: function(Container) {
            var Input = jQuery('#' + Container + ' input');
            for (var i = 0; i < Input.length; i++) {
                if (Input.eq(i).hasClass('datepicker')) {
                    Input.eq(i).datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: 'c-20:c+10' });
                }
                else if (Input.eq(i).hasClass('integer') || Input.eq(i).hasClass('postalcode')) {
                    Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
                        Value = Value.replace(new RegExp('[^0-9\.]', 'gi'), '');

						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}

						jQuery(this).val(Value);
                    });
                }
				else if (Input.eq(i).hasClass('alphabet')) {
					Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
						Value = Value.replace(new RegExp('[^a-z\ ]', 'gi'), '');

						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}

						jQuery(this).val(Value);
					});
				}
				else if (Input.eq(i).hasClass('float')) {
					Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
						Value = Value.replace(new RegExp('[^0-9\.]', 'gi'), '');

						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}

						jQuery(this).val(Value);
					});
				}
            }
        },
        Validation: function(Container, Param) {
			Param.Inline = (Param.Inline == null) ? false : Param.Inline;

            var ArrayError = [];
			jQuery('.CntWarning').remove();
            
            var Input = jQuery('#' + Container + ' input');
            for (var i = 0; i < Input.length; i++) {
                Input.eq(i).removeClass('ui-state-highlight');
                
                if (Input.eq(i).hasClass('required')) {
                    var Value = jQuery.trim(Input.eq(i).val());
                    
                    if (Value == '') {
                        Input.eq(i).addClass('ui-state-highlight');
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                    }
                }
                if (Input.eq(i).hasClass('integer') || Input.eq(i).hasClass('datepicker')) {
                    var Value = jQuery.trim(Input.eq(i).val());
                    var ValueResult = Value.replace(new RegExp('[^0-9\-]', 'gi'), '');
                    
                    if (Value != ValueResult) {
                        Input.eq(i).addClass('ui-state-highlight');
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                    }
                }
                if (Input.eq(i).hasClass('datepicker')) {
                    var Result = true;
                    var Value = jQuery.trim(Input.eq(i).val());
                    var ArrayValue = Value.split('-');
                    
                    if (Value.length == 0) {
                        Result = true;
                    } else if (ArrayValue.length != 3) {
                        Result = false;
                    } else if (ArrayValue[0] == '' || ArrayValue[1] == '' || ArrayValue[2] == '') {
                        Result = false;
                    }
                    
                    if (!Result) {
                        Input.eq(i).addClass('ui-state-highlight');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
                    }
                }
                if (Input.eq(i).hasClass('email') && ! Site.IsValidEmail(Input.eq(i).val())) {
					if (Input.eq(i).val() != '') {
						Input.eq(i).addClass('ui-state-highlight');
						ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
					}
                }
                if (Input.eq(i).hasClass('postalcode') && (Input.eq(i).val().length != 0 && Input.eq(i).val().length != 5)) {
                    Input.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                }
                if (Input.eq(i).hasClass('year') && (Input.eq(i).val().length != 0 && Input.eq(i).val().length != 4)) {
                    Input.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                }
            }
            
            var Select = jQuery('#' + Container +' select');
            for (var i = 0; i < Select.length; i++) {
                if (Select.eq(i).hasClass('required') && (Select.eq(i).val() == '' || Select.eq(i).val() == '-')) {
                    Select.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Select.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Select.eq(i));
                } else {
                    Select.eq(i).removeClass('ui-state-highlight');
                }
            }
            
            var TextArea = jQuery('#' + Container +' textarea');
            for (var i = 0; i < TextArea.length; i++) {
                var Value = TextArea.eq(i).val();
                Value = jQuery.trim(Value);
                
                if (TextArea.eq(i).hasClass('required') && TextArea.eq(i).val() == '') {
                    TextArea.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = TextArea.eq(i).attr('alt');
                } else {
                    TextArea.eq(i).removeClass('ui-state-highlight');
                }
            }
            
            return ArrayError;
        },
        GetValue: function(Container) {
			var PrefixCheck = Container.substr(0, 1);
			if (! Func.InArray(PrefixCheck, ['.', '#'])) {
				Container = '#' + Container;
			}

            var Data = Object();
			var set_value = function(obj, name, value) {
				if (typeof(name) == 'undefined') {
					return obj;
				} else if (name.length < 3) {
					obj[name] = value;
					return obj;
				}

				var endfix = name.substr(name.length - 2, 2);
				if (endfix == '[]') {
					var name_valid = name.replace(endfix, '');
					if (obj[name_valid] == null) {
						obj[name_valid] = [];
					}
					obj[name_valid].push(value);
				} else {
					obj[name] = value;
				}

				return obj;
			}
            
            var Input = jQuery(Container + ' input, ' + Container + ' select, ' + Container + ' textarea');
            for (var i = 0; i < Input.length; i++) {
				var name = Input.eq(i).attr('name');
				var value = Input.eq(i).val();

				if (Input.eq(i).attr('type') == 'checkbox') {
					var Checked = Input.eq(i).attr('checked');
					if (typeof(Checked) == 'string' && Checked == 'checked') {
						Data = set_value(Data, name, value);
					} else {
						Data = set_value(Data, name, 0);
					}
				} else {
					Data = set_value(Data, name, value);
				}
            }

            return Data;
        }
    }
}

var Func = {
	ArrayToJson: function(Data) {
		var Temp = '';
		for (var i = 0; i < Data.length; i++) {
			Temp = (Temp.length == 0) ? Func.ObjectToJson(Data[i]) : Temp + ',' + Func.ObjectToJson(Data[i]);
		}
		return '[' + Temp + ']';
	},
	InArray: function(Value, Array) {
		var Result = false;
		for (var i = 0; i < Array.length; i++) {
			if (Value == Array[i]) {
				Result = true;
				break
			}
		}
		return Result;
	},
	IsEmpty: function(value) {
		var Result = false;
		if (value == null || value == 0) {
			Result = true;
		} else if (typeof(value) == 'string') {
			value = Helper.Trim(value);
			if (value.length == 0) {
				Result = true;
			}
		}

		return Result;
	},
	ObjectToJson: function(obj) {
		var str = '';
		for (var p in obj) {
			if (obj.hasOwnProperty(p)) {
				if (obj[p] != null) {
					str += (str.length == 0) ? str : ',';
					str += '"' + p + '":"' + obj[p] + '"';
				}
			}
		}
		str = '{' + str + '}';
		return str;
	},
	SetValue: function(Param) {
		// Func.SetValue({ Action : 'City', ForceID: Param.city_id, Combo: WinGateway.city });

		Ext.Ajax.request({
			url: Web.HOST + '/index.php/combo',
			params: { Action : Param.Action, ForceID: Param.ForceID },
			success: function(Result) {
				Param.Combo.store.loadData(eval(Result.responseText));
				Param.Combo.setValue(Param.ForceID);
			}
		});
	},
	SyncComboParam: function(c, Param) {
		var ArrayConfig = ['renderTo', 'name', 'fieldLabel', 'anchor', 'id', 'allowBlank', 'blankText', 'tooltip', 'iconCls', 'width', 'listeners', 'value'];
		for (var i = 0; i < ArrayConfig.length; i++) {
			if (Param[ArrayConfig[i]] != null) {
				c[ArrayConfig[i]] = Param[ArrayConfig[i]];
			}
		}
		return c;
	},
	Trim: function(value) {
		return value.replace(/^\s+|\s+$/g,'');
	},
	ajax: function(p) {
		p.is_json = (p.is_json == null) ? 1 : p.is_json;
		
		$.ajax({ type: 'POST', url: p.url, data: p.param, success: function(data) {
			if (p.is_json == 1) {
				eval('var result = ' + data);
				p.callback(result);
			} else {
				p.callback(data);
			}
		} });
	}
}