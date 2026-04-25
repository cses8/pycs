<template>
  <span class="ql-formats">
    <select class="ql-header" aria-label="Text style">
      <option value="1">Heading</option>
      <option value="2">Subheading</option>
      <option selected>Normal</option>
    </select>
    <select class="ql-font" aria-label="Font">
      <option selected />
      <option value="serif" />
      <option value="monospace" />
    </select>
  </span>
  <span class="ql-formats">
    <button class="ql-bold" type="button" aria-label="Bold" />
    <button class="ql-italic" type="button" aria-label="Italic" />
    <button class="ql-underline" type="button" aria-label="Underline" />
    <button class="ql-strike" type="button" aria-label="Strike" />
  </span>
  <span class="ql-formats">
    <button class="ql-blockquote" type="button" aria-label="Quote" />
    <button class="ql-code-block" type="button" aria-label="Code block" />
  </span>
  <span class="ql-formats">
    <select class="ql-color" aria-label="Text color" />
    <select class="ql-background" aria-label="Highlight color" />
  </span>
  <span class="ql-formats">
    <button class="ql-list" value="ordered" type="button" aria-label="Numbered list" />
    <button class="ql-list" value="bullet" type="button" aria-label="Bulleted list" />
    <button class="ql-indent" value="-1" type="button" aria-label="Decrease indent" />
    <button class="ql-indent" value="+1" type="button" aria-label="Increase indent" />
  </span>
  <span class="ql-formats">
    <select class="ql-align" aria-label="Alignment">
      <option selected />
      <option value="center" />
      <option value="right" />
      <option value="justify" />
    </select>
  </span>
  <span class="ql-formats">
    <button class="ql-link" type="button" aria-label="Link" />
    <button class="ql-image" type="button" aria-label="Image" />
    <button class="ql-clean" type="button" aria-label="Clear formatting" />
  </span>
</template>
