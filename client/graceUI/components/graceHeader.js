// graceUI/components/graceHeader.js
Component({
  properties: {
    background: {
      type: String,
      value: "#F8F8F8"
    },
    height: {
      type: String,
      value: "44px"
    },
    top: {
      type: String,
      value: "0px"
    }
  },
  data : {
    topVal : '0px'
  },
  ready: function(){
    var sys = wx.getSystemInfoSync();
    console.log(sys.statusBarHeight)
    this.setData({ topVal : sys.statusBarHeight+'px'});
  }
})
