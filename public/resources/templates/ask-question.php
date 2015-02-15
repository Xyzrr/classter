<div class="container">
  <div class="wrapper">
    <form role="form" class="ask-question-form" action="" method="post">
      <div class="body">
        <div class="form-group">
          <h1>Ask a question</h1>
        </div>
        <div class="form-group">
          <input type="text" name="title" id="title" class="form-control" placeholder="Question Title" autofocus>
        </div>
        <div class="form-group">
          <?= $editor ?>
        </div>
        <div>
          <button class="form-control btn-primary" id="target-selector-course">Select Subject</button>
        </div>
      </div>
      <div class="footer">
        <button type="submit" class="btn btn-primary" id="post-question">Submit Question</button>
        <a href="../" class="btn btn-default">Cancel</a>
      </div>
    </form>
    <div class="ask-question-helper">
      <div class="body">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="set-course" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <input type="text" class="form-control" id="course-search"/>
      </div>
      <div class="modal-body modal-nopadding">
        <div class="wrapper modal-wrapper" id="course-search-results">
          Your search results will appear here.
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>