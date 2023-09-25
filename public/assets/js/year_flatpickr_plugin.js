   // plugin js file
    /**
 * Flatpickr Year Select Plugin.
 * @author Labi Romabravo
 */

/**
 *
 * @returns {Function}
 */
const yearDropdownPlugin = function (pluginConfig) {
    var defaultConfig = {
        text: '',
        theme: "light",
        date: new Date(),
        yearStart: 100,
        yearEnd: 2,

    };

    var config = {};
    for (var key in defaultConfig) {
        config[key] = pluginConfig && pluginConfig[key] !== undefined ? pluginConfig[key] : defaultConfig[key];
    }

    var getYear = function (value) {
        var date = value.split("/");
        return parseInt(date[2], 10);
    }

    var currYear = new Date().getFullYear();
    var selectedYear = getYear(config.date);

    var yearDropdown = document.createElement("select");

    var createSelectElement = function (year) {
        var start = new Date().getFullYear() - config.yearStart;
        var end = currYear + config.yearEnd;

        for (var i = end; i >= start; i--) {
            var option = document.createElement("option");
            option.value = i;
            option.text = i;
            yearDropdown.appendChild(option);
        }
        yearDropdown.value = selectedYear;
    };

    return function (fp) {
        fp.yearSelectContainer = fp._createElement(
            "div",
            "flatpickr-year-select " + config.theme + "Theme",
            config.text
        );

        fp.yearSelectContainer.tabIndex = -1;
        createSelectElement(selectedYear);
        yearDropdown.addEventListener('change', function (evt) {
            var year = evt.target.options[evt.target.selectedIndex].value;
            fp.changeYear(year);
        });

        fp.yearSelectContainer.append(yearDropdown);

        return {
            onReady: function onReady() {
                var name = fp.monthNav.className;
                const yearInputCollection = fp.calendarContainer.getElementsByClassName(name);
                const el = yearInputCollection[0];
                el.parentNode.insertBefore(fp.yearSelectContainer, el.parentNode.firstChild);
            }
        };
    };
}

export default yearDropdownPlugin
// if (typeof module !== "undefined")
//     module.exports = yearDropdownPlugin;
