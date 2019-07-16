//app.js
App({
  onLaunch: function () {
    
  },
  checkLogin: function (backpage, backtype){
    var SUID = wx.getStorageSync('SUID');
    if (SUID == '') {
      wx.redirectTo({
        url: '../login/index?backpage=' + backpage + '&backtype=' + backtype
      });
      return false;
    }
    return [SUID];
  }
})