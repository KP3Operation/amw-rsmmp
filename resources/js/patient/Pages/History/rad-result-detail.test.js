/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import RadResultDetailPage from "./RadResultDetail.vue";

describe("RadResultDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(RadResultDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
