export default {
  notification(title, text) {
     return {
        title: title,
        text: text,
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }
  },
}
