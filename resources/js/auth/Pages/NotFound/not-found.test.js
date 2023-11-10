/**
 * @vitest-environment happy-dom
 */

import NotFoundPage from "@auth/Pages/NotFound/NotFound.vue";
import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";

describe("NotFoundPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(NotFoundPage);
        expect(wrapper.exists()).toBe(true);
    });
});
