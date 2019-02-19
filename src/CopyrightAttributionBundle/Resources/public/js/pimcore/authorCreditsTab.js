pimcore.registerNS("pimcore.bundle.CopyrightAttributionBundle.Tab");

pimcore.bundle.CopyrightAttributionBundle.Tab = Class.create({

    title: t('copyright_attribution_menu'),
    iconCls: 'copyright_attribution_icon',
    src: '/admin/copyright-attribution/content',
    id: 'copyright_attribution_tab',

    initialize: function () {
        this.getLayout();
    },

    getLayout: function () {
        if (this.layout == null) {
            this.reloadButton = new Ext.Button({
                text: t("reload"),
                iconCls: "pimcore_icon_reload",
                handler: this.reload.bind(this)
            });

            // create new panel
            this.layout = new Ext.Panel({
                id: this.id,
                title: this.title,
                iconCls: this.iconCls,
                layout: "fit",
                border: false,
                autoScroll: true,
                closable: true,
                containerScroll: true,
                html: '<iframe style="border:none" src="' + this.src + '" width="100%" height="99%"></iframe>',
                tbar: [this.reloadButton]
            });

            // add event listener
            var layoutId = this.id;
            this.layout.on("destroy", function () {
                pimcore.globalmanager.remove(layoutId);
                pimcore.layout.refresh();
            }.bind(this));

            // add panel to pimcore panel tabs
            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.layout);
            tabPanel.setActiveItem(this.id);

            // update layout
            pimcore.layout.refresh();
        }
        return this.layout;
    },

    reload: function () {
        try {
            Ext.get('copyright_attribution_tab').dom.src = this.src;
        } catch (e) {
            console.log(e);
        }
    }
});
