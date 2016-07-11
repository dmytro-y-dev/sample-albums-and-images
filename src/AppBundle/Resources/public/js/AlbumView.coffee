App = App || {}

App.AlbumView = Backbone.Marionette.ItemView.extend
  tagName: 'li',
  template: '#template-albumItemView'

App.AlbumListView = Backbone.Marionette.CollectionView.extend
  tagName: 'ul',
  template: '#template-albumListCollectionView',
  childView: App.AlbumView,
  childViewContainer: '#albums'
