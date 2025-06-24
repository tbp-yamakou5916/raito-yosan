<style>
    .img {
        position: relative;
    }
    .size {
        width: calc(100% - 15px);
    }
    .preview_field {
        position: absolute;
        top: 0;
        left: 7.5px;
        border-radius: 6px;
        overflow: hidden;
        text-align: center;
    }
    .preview_field img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 50% 50%;
    }
    .drop_area {
        position: absolute;
        top: 0;
        left: 7.5px;
        border: 1px dashed #aaa;
        border-radius: 6px;
        cursor: pointer;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .btn_clear {
        position: absolute;
        top: 2px;
        right: 9.5px;
        cursor: pointer;
    }
    .btn_clear:hover {
        opacity: 0.5;
    }
    .input_file {
        opacity: 0;
    }
    .input_file:focus {
        opacity: 1;
    }
</style>
