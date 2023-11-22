/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import LabResultDetailPage from "./LabResultDetail.vue";

describe("LabResultDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(LabResultDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
