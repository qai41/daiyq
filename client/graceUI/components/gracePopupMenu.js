// graceUI/components/gracePopupMenu.js
Component({
  properties: {
    show: {
      type: Boolean,
      value: false
    },
    top: {
      type: Number,
      value: 0
    },
    bgColor: {
      type: String,
      value: '#4c4c4c'
    },
    menuWidth: {
      type: String,
      value: '300rpx'
    }
  },
  data: {

  },
  methods: {
    hideMenu: function () {
      this.triggerEvent('hideMenu');
    }
  }
})
