(function (Current) {
    "use strict";
 
    Current.Clone = function Clone(array) {
        return array.slice(0);
    };
 
    Current.Split = function Split(list, delimiter) {
        return $.grep((list || '').split(delimiter), function (a) {
            return $.trim(a || '').length;
        });
    };
 
    Current.Shuffle = function Shuffle(list) {
        for (var j, x, i = list.length; i; j = Math.floor(Math.random() * i), x = list[--i], list[i] = list[j], list[j] = x);
        return list;
    };
 
    Current.Merge = function Merge() {
        return [].concat.apply([], arguments);
    };
 
    Current.Clear = function Clear(list) {
        while (list.length) {
            list.pop();
        }
    };
 
    Current.First = function First(list, checker) {
        var retVal = null;;

        $.each(list || [], function () {
            if (checker(this)) {
                retVal = this;
                return false;
            }
        });

        return retVal;
    };
 
    Current.RemoveElement = function RemoteElement(list, element, matcher) {
        var elementIndex = DevDes.Arrays.Find(list, function (el) {
            if (typeof (matcher) === 'string') {
                return element[matcher] === el[matcher];
            }
        });

        if (elementIndex > -1) {
            list.splice(elementIndex, 1);
        }
    };
 
    Current.Find = function Find(list, checker) {
        var retVal = -1;

        $.each(list || [], function (i) {
            if (checker(this)) {
                retVal = i;
                return false;
            }
        });

        return retVal;
    };

    Current.FilterToMatrix = function FilterToMatrix(list, maxColumn) {
        var retVal = [];
 
        $.each(list, function () {
            if (!retVal.length) retVal.push([]);
            if (retVal[retVal.length - 1].length === maxColumn) {
                retVal.push([]);
            }
 
            retVal[retVal.length - 1].push(this);            
        });
 
        return retVal;
    }
})(defineNamespace("Helpers.Core.Arrays"));