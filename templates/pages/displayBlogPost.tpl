{% extends 'component/framework.tpl' %}


{% block page_title %}
    {{ renderActiveBlogPostTitle() }}
{% endblock %}



{% block mainContent %}
    {{ renderActiveBlogPostBody() }}
{% endblock %}
