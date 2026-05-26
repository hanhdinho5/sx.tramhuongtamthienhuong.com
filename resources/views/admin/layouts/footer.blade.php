<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>{{ $setting ? $setting->home_name : '' }}</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
        {{ __('error.designed_by') }} <a target="_blank"
                                         href="{{ $setting ? $setting->author_social : '' }}">{{ $setting ? $setting->author_name : '' }}</a>
    </div>
</footer>
