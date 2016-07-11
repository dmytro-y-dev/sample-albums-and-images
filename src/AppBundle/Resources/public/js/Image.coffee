App = App || {}

App.Image = Backbone.Model.extend
  defaults:
    id : null
    filename : ''
    description : ''

App.ImagesCollection = Backbone.Collection.extend
  model: App.Image

App.ImageView = Backbone.Marionette.ItemView.extend
  tagName: 'div.grid-item',
  template: '#template-albumItemView'

App.ImagesListView = Backbone.Marionette.CollectionView.extend
  tagName: '#images',
  childView: App.ImageView
