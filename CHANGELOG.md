# Changelog

## 1.1.0 - 2026-10-29

- Require PHP 8.3+ and Laravel 11, 12, and 13.
- Type the Taggable API, including an explicit nullable union on `untag()`.
- Normalize tag names in `hasTags`, `withAnyTag`, and `withAllTags` the same way tagging does.
- Ignore blank tag names when attaching tags.
- Dispatch `TagAttached` and `TagDetached` after tags actually change, once the surrounding transaction commits.
- Add Larastan 3, Pint, and a Slipway-generated GitHub Actions pipeline across PHP 8.3–8.5.
