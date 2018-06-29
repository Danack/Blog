


<div class="row panel panel-default pastLinks">
  <div class="col-md-12">
    {# inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost' #}
    <ul class="nav nav-list smallPadding">
        {{ renderBlogPostList() }}
        <li><a href='/rss'>RSS feed</a></li>
        <li><a href='http://docs.basereality.com'>RFCs + slides</a></li>
    </ul>
  </div>
</div>