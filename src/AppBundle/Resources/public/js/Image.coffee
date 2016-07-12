App = App || {}

App.Image = Backbone.Model.extend
  defaults:
    id: null
    filename: ''
    description: ''

App.ImagesCollection = Backbone.Collection.extend
  model: App.Image

App.ImageView = Backbone.Marionette.ItemView.extend
  tagName: 'div',
  className: 'grid-item',
  template: '#template-imageItemView'

App.ImagesListView = Backbone.Marionette.CollectionView.extend
  tagName: 'div',
  className: 'grid'
  childView: App.ImageView

  onDomRefresh : () ->
    $('.grid').hide()

    $('.grid').imagesLoaded () ->
      $('.grid').show () ->
        $('.grid').masonry
          itemSelector : '.grid-item'