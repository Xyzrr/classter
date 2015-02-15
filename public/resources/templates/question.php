  <div class="container">
      <div class="wrapper">
          <div class="main-column">
              <div id="question" data-id="<?= $postID ?>">
              </div>
              <div id="answers">
              </div>
              <div id="answer-box">
                  <?= $answerBox ?>
              </div>
          </div>
      </div>
  </div>

<div class="modal fade" id="delete-question-confirmation" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Delete Question?</h4>
      </div>
      <div class="modal-body">
          <p>You will lose all answers and comments associated with it.</p>
          <p>Please do not delete a question simply because it's not valuable to you anymore; it may help wandering community members in the future.</p>
          <p>A question should only be deleted if it is considered off-topic, low quality, or a duplicate of another question.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" id="cancel-delete-question" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="delete-question-confirm">Delete Question</button>
      </div>
    </div>
  </div>
</div>