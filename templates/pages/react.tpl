{extends file='component/framework'}
{inject name='scriptInclude' type='ScriptHelper\ScriptInclude'}

{block name='mainContent'}

  <script src="/js/react/babel.min.js"></script>
  <script src="/js/react/react.js"></script>
  <script src="/js/react/react-dom.js"></script>

<div class="row">
  Hello, I am a react test.

  <div id="toggle_text"></div>

  <div id="template_editor"></div>



</div>

  <!-- todo - switch to compacted version for release.
  <script src="/dist/foo.js" type="text/javascript"></script> -->

  <script src="/js/react/src/template_editor.jsx" type="text/babel"></script>
  <!-- <script src="/js/react/src/hello_world.jsx" type="text/babel"></script>  -->
  <script src="/js/react/src/internal_state.jsx" type="text/babel"></script>

{/block}

