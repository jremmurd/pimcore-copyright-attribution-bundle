pimcore.registerNS("pimcore.bundle.CopyrightAttributionBundle");

pimcore.bundle.CopyrightAttributionBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.bundle.CopyrightAttributionBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        this.addCopyrightAttributionMenuItem();
    },

    parseResponse: function (response) {
        var responseData = {};
        try {
            responseData = JSON.parse(response.responseText);
        } catch (e) {
            console.error("Error parsing response.");
        }
        return responseData;
    },

    addCopyrightAttributionMenuItem : function(){
        var menu = pimcore.globalmanager.get("layout_toolbar").extrasMenu;

        menu.add({
            text: t("copyright_attribution_menu"),
            iconCls: "copyright_attribution_icon",
            handler: function () {
                if (pimcore.globalmanager.get("copyright_attribution_tab")) {
                    return Ext.getCmp("pimcore_panel_tabs").setActiveItem("copyright_attribution_tab");
                } else {
                    return pimcore.globalmanager.add("copyright_attribution_tab", new pimcore.bundle.CopyrightAttributionBundle.Tab());
                }
            }
        });
    }
});

var CopyrightAttributionBundle = new pimcore.bundle.CopyrightAttributionBundle();
