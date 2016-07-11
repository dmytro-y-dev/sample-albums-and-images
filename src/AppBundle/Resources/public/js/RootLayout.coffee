App = App || {}

App.RootLayout = Backbone.Marionette.LayoutView.extend
  template: "#template-rootLayoutView"

  regions :
    sidebar : '#sidebar'
    main : '#main'

  showSidebar : () ->
    this.sidebar.show(new App.AlbumListView())