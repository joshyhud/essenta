<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Styled Page</title>
  <link rel="stylesheet" href="/wp-content/themes/b9-boilerplate/dist/css/style.min.css">
</head>

<body>
  <section>
    <div class="container">
      <h1>Styleguide page</h1>
    </div>
  </section>

  <section>
    <div class="container">
      <h2>Headings</h2>
      <h1>Heading 1</h1>
      <h2>Heading 2</h2>
      <h3>Heading 3</h3>
      <h4>Heading 4</h4>
      <h5>Heading 5</h5>
      <h6>Heading 6</h6>
    </div>
  </section>

  <section>
    <div class="container">
      <h2>Paragraph</h2>
      <p>This is a paragraph of text. It is styled according to the stylesheet.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <h2>Inputs</h2>
      <input type="text" id="text-input" name="text-input" placeholder="Enter text here">
      <input type="email" id="email-input" name="email-input" placeholder="Enter your email">
      <input type="password" id="password-input" name="password-input" placeholder="Enter your password">

      <div id="checkbox-group">
        <label><input type="checkbox" name="checkbox1"> Option 1</label>
        <label><input type="checkbox" name="checkbox2"> Option 2</label>
        <label><input type="checkbox" name="checkbox3"> Option 3</label>
      </div>

      <div id="radio-group">
        <label><input type="radio" name="radio" value="1"> Option 1</label>
        <label><input type="radio" name="radio" value="2"> Option 2</label>
        <label><input type="radio" name="radio" value="3"> Option 3</label>
      </div>

      <input type="range" id="range-input" name="range-input" min="0" max="100">

      <label class="toggle">
        <input type="checkbox" id="toggle-button" name="toggle-button">
        <span class="slider round"></span>
      </label>

      <textarea placeholder="Textarea"></textarea>
    </div>
  </section>

  <section>
    <div class="container">
      <h2>Buttons</h2>
      <section class="buttons-primary">
        <button class="btn__primary">Button CTA</button>
        <button class="btn__primary" disabled>Button CTA</button>
        <button class="btn__primary arw-left arrow-right">Button CTA</button>
        <button class="btn__primary arw-left arrow-right" disabled>Button CTA</button>
        <button class="btn__primary arw-right arrow-right">Button CTA</button>
        <button class="btn__primary arw-right arrow-right" disabled>Button CTA</button>
      </section>

      <section class="buttons-secondary">
        <button class="btn__secondary">Button CTA</button>
        <button class="btn__secondary" disabled>Button CTA</button>
        <button class="btn__secondary arw-left arrow-right">Button CTA</button>
        <button class="btn__secondary arw-left arrow-right" disabled>Button CTA</button>
        <button class="btn__secondary arw-right arrow-right">Button CTA</button>
        <button class="btn__secondary arw-right arrow-right" disabled>Button CTA</button>
      </section>

      <section class="buttons-tertiary">
        <button class="btn__tertiary">Button CTA</button>
        <button class="btn__tertiary" disabled>Button CTA</button>
        <button class="btn__tertiary arw-left arrow-right">Button CTA</button>
        <button class="btn__tertiary arw-left arrow-right" disabled>Button CTA</button>
        <button class="btn__tertiary arw-right arrow-right">Button CTA</button>
        <button class="btn__tertiary arw-right arrow-right" disabled>Button CTA</button>
      </section>
    </div>
  </section>
</body>

</html>